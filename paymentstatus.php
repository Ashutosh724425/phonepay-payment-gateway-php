<?php
session_start();
require_once "./utils/config.php";
require_once "./utils/common.php";
require_once "./utils/SendMail.php";

// Validate POST data from payment gateway
$transactionId = $_POST['transactionId'] ?? null;
$merchantId = $_POST['merchantId'] ?? null;

if (!$transactionId || !$merchantId) {
    error_log("Missing transaction or merchant ID in callback");
    header("Location: " . BASE_URL . "error.php?message=invalid_response");
    exit;
}

// Verify session data exists and matches
if (!isset($_SESSION['payment_data']) || 
    $_SESSION['payment_data']['transactionId'] !== $transactionId || 
    $_SESSION['payment_data']['merchantId'] !== $merchantId) {
    
    error_log("Session validation failed for transaction: {$transactionId}");
    
    // Attempt to recover from database
    require_once "./dbcon.php";
    $stmt = $con->prepare("SELECT * FROM payment_attempts WHERE transaction_id = ? AND merchant_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ss", $transactionId, $merchantId);
    $stmt->execute();
    $result = $stmt->get_result();
    $paymentData = $result->fetch_assoc();
    $stmt->close();
    
    if ($paymentData) {
        // Reconstruct session data from database
        $_SESSION['payment_data'] = [
            'email' => $paymentData['email'],
            'amount' => $paymentData['amount'],
            'transactionId' => $paymentData['transaction_id'],
            'merchantId' => $paymentData['merchant_id'],
            'name' => $paymentData['name'],
            'contact' => $paymentData['contact']
        ];
    } else {
        error_log("Could not recover payment data from database for transaction: {$transactionId}");
        header("Location: " . BASE_URL . "error.php?message=session_expired");
        exit;
    }
}

// Get data from session
$amount = $_SESSION['payment_data']['amount'];
$email = $_SESSION['payment_data']['email'];
$name = $_SESSION['payment_data']['name'];
$contact = $_SESSION['payment_data']['contact'];

// Check if payment attempt already exists
require_once "./dbcon.php";
$stmt = $con->prepare("SELECT id FROM payment_attempts WHERE transaction_id = ? AND merchant_id = ?");
$stmt->bind_param("ss", $transactionId, $merchantId);
$stmt->execute();
$result = $stmt->get_result();
$existingPayment = $result->fetch_assoc();
$stmt->close();

if (!$existingPayment) {
    // Insert new payment attempt
    $stmt = $con->prepare("
        INSERT INTO payment_attempts (
            transaction_id, merchant_id, email, amount, status, 
            name, contact, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $initialStatus = "PAYMENT_INITIATED";
    $stmt->bind_param("sssssss", 
        $transactionId, $merchantId, $email, $amount, $initialStatus,
        $name, $contact
    );
    $stmt->execute();
    $stmt->close();
} 

// API configuration
$url = (API_STATUS === "LIVE") 
    ? LIVESTATUSCHECKURL . $merchantId . "/" . $transactionId 
    : STATUSCHECKURL . $merchantId . "/" . $transactionId;

$saltKey = (API_STATUS === "LIVE") ? SALTKEYLIVE : SALTKEYUAT;
$saltIndex = SALTINDEX;

// Prepare checksum
$dataToHash = "/pg/v1/status/" . $merchantId . "/" . $transactionId . $saltKey;
$checksum = hash("sha256", $dataToHash) . "###" . $saltIndex;

// Send GET request
$headers = [
    "Content-Type: application/json",
    "accept: application/json",
    "X-VERIFY: " . $checksum,
    "X-MERCHANT-ID: " . $merchantId,
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

if ($error) {
    error_log("Status check cURL error for transaction {$transactionId}: {$error}");
    header("Location: " . BASE_URL . "error.php?message=status_check_failed");
    exit;
}

$responseDecoded = json_decode($response, true);

// Update final payment status
$finalStatus = isset($responseDecoded['code']) ? $responseDecoded['code'] : 'UNKNOWN';
$stmt = $con->prepare("
    UPDATE payment_attempts 
    SET status = ?, 
        updated_at = NOW(),
        response_data = ?
    WHERE transaction_id = ? 
    AND merchant_id = ?
");
$responseJson = json_encode($responseDecoded);
$stmt->bind_param("ssss", $finalStatus, $responseJson, $transactionId, $merchantId);
$stmt->execute();
$stmt->close();

if (isset($responseDecoded['success']) && $responseDecoded['code'] === "PAYMENT_SUCCESS") {
    try {
        $_SESSION['payment_success'] = [
            'transactionId' => $transactionId,
            'email' => $email,
            'amount' => $amount,
            'name' => $name,
            'contact' => $contact
        ];

        // $mailer = new Mail();
        // $mailer->sendPaymentConfirmation($email, $name, $transactionId, $amount);
        
        header("Location: " . BASE_URL . "success.php?tid=" . $transactionId);
        exit;

    } catch (Exception $e) {
        error_log("Mailer error: " . $e->getMessage());
        return false;
    }
} else {
    // Log the failure
    error_log("Payment failed for transaction {$transactionId}: " . json_encode($responseDecoded));

    // Clear session after failed payment
    unset($_SESSION['payment_data']);

    // Redirect to failure page
    header("Location: " . BASE_URL . "failure.php?tid=" . $transactionId);
    exit;
}