<?php
session_start(); // Ensure session is started at the beginning of the file

// Check if session data exists
if (isset($_SESSION['payment_success'])) {
    $paymentData = $_SESSION['payment_success'];
    // Clear the session data after use
    unset($_SESSION['payment_success']);
} else {
    // Handle error or redirect if session data is not found
    header("Location: " . BASE_URL . "error.php?message=session_expired");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .success-card {
            background: white;
            padding: 3rem;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .success-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .checkmark-circle {
            width: 150px;
            height: 150px;
            background: #f0fdf4;
            border-radius: 50%;
            margin: 0 auto 2rem;
            position: relative;
            animation: scaleIn 0.5s ease-out;
        }

        .checkmark {
            color: #22c55e;
            font-size: 80px;
            line-height: 150px;
            animation: checkScale 0.5s ease-out 0.5s both;
        }

        h1 {
            color: #16a34a;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.5s ease-out 0.3s both;
        }

        .transaction-details {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.5s ease-out 0.4s both;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 600;
        }

        .success-message {
            color: #475569;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-top: 1.5rem;
            animation: fadeInUp 0.5s ease-out 0.5s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes checkScale {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 2rem;
                margin: 1rem;
            }

            .checkmark-circle {
                width: 120px;
                height: 120px;
            }

            .checkmark {
                font-size: 60px;
                line-height: 120px;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="checkmark-circle">
            <span class="checkmark">✓</span>
        </div>
        <h1>Payment Successful!</h1>
        
        <div class="transaction-details">
            <div class="detail-item">
                <span class="detail-label">Transaction ID</span>
                <span class="detail-value"><?php echo htmlspecialchars($paymentData['transactionId']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?php echo htmlspecialchars($paymentData['email']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Amount</span>
                <span class="detail-value"><?php echo htmlspecialchars($paymentData['amount']); ?></span>
            </div>
        </div>

        <p class="success-message">
            Thank you for your payment! We've received your purchase request<br>and will process it shortly.
        </p>
    </div>
</body>
</html>