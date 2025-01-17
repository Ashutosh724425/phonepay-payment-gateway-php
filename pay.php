<?php
session_start();
require_once "./utils/config.php";
require_once "./utils/common.php";
include "./dbcon.php";

// Initialize error handler
function handleError($message) {
    error_log($message);
    die($message);
}

// Validate POST data
if (!isset($_POST['name'], $_POST['email'], $_POST['contact'], $_POST['amount'])) {
    handleError("Error: Missing required POST data.");
}

// Clean and validate input data
$name = trim(strip_tags($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$contact = preg_replace('/[^0-9]/', '', $_POST['contact']);
$amount = (float) $_POST['amount'];

// Validate each field
if (empty($name)) {
    handleError("Error: Name is required.");
}
if (!$email) {
    handleError("Error: Invalid email format.");
}
if (strlen($contact) < 10) {
    handleError("Error: Invalid contact number.");
}
if ($amount <= 0) {
    handleError("Error: Invalid amount.");
}

// Generate unique transaction ID
$transactionId = "MT-" . uniqid() . "-" . time();
$merchantId = (API_STATUS === "LIVE") ? MERCHANTIDLIVE : MERCHANTIDUAT;

// Store in session
$_SESSION['payment_data'] = [
    'name' => $name,
    'email' => $email,
    'contact' => $contact,
    'amount' => $amount,
    'transaction_id' => $transactionId,
    'merchant_id' => $merchantId,
    'timestamp' => time()
];

// Verify session storage
if (!isset($_SESSION['payment_data'])) {
    handleError("Error: Failed to store session data.");
}

// Log payment initiation
error_log("Payment Initiation - Transaction: {$transactionId}, Amount: {$amount}, Email: {$email}");

// Store initial payment record
try {
    $stmt = $con->prepare("
        INSERT INTO payment_attempts 
        (transaction_id, merchant_id, name, email, contact, amount, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'INITIATED', NOW())
    ");
    
    $stmt->bind_param("sssssd", 
        $transactionId, 
        $merchantId,
        $name,
        $email,
        $contact,
        $amount
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to store payment record: " . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    // Continue even if DB insert fails - don't block payment
}

// Prepare payment gateway request
$saltKey = (API_STATUS === "LIVE") ? SALTKEYLIVE : SALTKEYUAT;
$url = (API_STATUS === "LIVE") ? LIVEURLPAY : UATURLPAY;
$saltIndex = SALTINDEX;

$payLoad = [
    'merchantId' => $merchantId,
    'merchantTransactionId' => $transactionId,
    'merchantUserId' => "MUID-" . uniqid(),
    'amount' => $amount * 100,
    'redirectUrl' => BASE_URL . REDIRECTURL,
    'redirectMode' => "POST",
    'callbackUrl' => BASE_URL . REDIRECTURL,
    'mobileNumber' => $contact,
    'paymentInstrument' => [
        'type' => "PAY_PAGE",
    ],
];

$jsonEncodedPayload = json_encode($payLoad);
$payloadBase64 = base64_encode($jsonEncodedPayload);
$dataToHash = $payloadBase64 . "/pg/v1/pay" . $saltKey;
$checksum = hash("sha256", $dataToHash) . "###" . $saltIndex;

$requestPayload = json_encode(['request' => $payloadBase64]);
$headers = [
    "Content-Type: application/json",
    "X-VERIFY: " . $checksum,
    "accept: application/json",
];

// Payment gateway request with retry mechanism
$retryCount = 0;
$maxRetries = 3; // Reduced from 5 to 3 to minimize testing costs

while ($retryCount < $maxRetries) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $requestPayload,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        error_log("Payment cURL Error for {$transactionId}: " . $error);
        $retryCount++;
        continue;
    }

    $responseDecoded = json_decode($response, true);

    if (isset($responseDecoded['success']) && $responseDecoded['success'] === true) {
        // Update payment record status
        try {
            $stmt = $con->prepare("
                UPDATE payment_attempts 
                SET status = 'REDIRECTED', 
                    updated_at = NOW() 
                WHERE transaction_id = ?
            ");
            $stmt->bind_param("s", $transactionId);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            error_log("Failed to update payment status: " . $e->getMessage());
            // Continue even if update fails
        }

        // Redirect to payment gateway
        $paymentUrl = $responseDecoded['data']['instrumentResponse']['redirectInfo']['url'];
        header("Location: " . $paymentUrl);
        exit;
    } elseif (isset($responseDecoded['code']) && $responseDecoded['code'] === "TOO_MANY_REQUESTS") {
        $waitTime = pow(2, $retryCount);
        error_log("Payment retry {$retryCount} for {$transactionId}");
        sleep($waitTime);
        $retryCount++;
    } else {
        error_log("Payment error for {$transactionId}: " . json_encode($responseDecoded));
        $retryCount++;
    }
}

// If we reach here, all retries failed
handleError("Payment initiation failed after {$maxRetries} attempts. Please try again later.");
?>