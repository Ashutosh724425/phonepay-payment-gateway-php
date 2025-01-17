<?php
require_once "./utils/config.php";

// Get error message from URL parameter
$error_message = isset($_GET['message']) ? urldecode($_GET['message']) : 'An unknown error occurred';
$transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : '';

// Function to get a user-friendly error message
function getUserFriendlyError($error_message) {
    // Map of technical errors to user-friendly messages
    $error_map = [
        'Missing required fields' => 'Please fill in all required information.',
        'Failed to insert payment record' => 'There was an issue processing your payment. Please try again.',
        'Payment gateway error' => 'The payment service is temporarily unavailable. Please try again later.',
        'Database connection failed' => 'We\'re experiencing technical difficulties. Please try again later.',
        'Invalid amount' => 'Please enter a valid payment amount.',
        'Invalid email format' => 'Please provide a valid email address.'
    ];

    // Return mapped message if exists, otherwise return generic message
    foreach ($error_map as $technical => $friendly) {
        if (stripos($error_message, $technical) !== false) {
            return $friendly;
        }
    }
    
    return 'An unexpected error occurred. Please try again or contact support.';
}

$user_friendly_message = getUserFriendlyError($error_message);

error_log("Payment Error - Transaction ID: $transaction_id - Error: $error_message");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .error-icon {
            color: #dc3545;
            font-size: 48px;
            margin-bottom: 20px;
        }
        .error-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .support-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="text-center mb-4">
                <div class="error-icon">
                    ❌
                </div>
                <h2 class="text-danger">Payment Failed</h2>
            </div>

            <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($user_friendly_message); ?>
            </div>

            <?php if ($transaction_id): ?>
            <div class="error-details">
                <h5>Error Details:</h5>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_id); ?></p>
                <p class="mb-0"><small>Please keep this ID for reference when contacting support.</small></p>
            </div>
            <?php endif; ?>

            <div class="support-info">
                <h5>Need Help?</h5>
                <p>If this issue persists, please contact our support team:</p>
                <ul class="list-unstyled">
                    <li>📧 Email: <?php echo COMPANY_EMAIL; ?></li>
                    <li>⏰ Support Hours: Monday - Friday, 9AM - 6PM</li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-primary me-2">Return to Home</a>
                <button onclick="window.history.back()" class="btn btn-secondary">Go Back</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 60000);
    </script>
</body>
</html>