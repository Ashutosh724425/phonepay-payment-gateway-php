<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
        }

        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
        }

        .booking-form--wrapper {
            max-width: 800px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .booking-form--wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .booking-form--inner {
            position: relative;
            z-index: 1;
        }

        .booking-form--inner h2 {
            color: #1f2937;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 1rem;
        }

        .booking-form--inner h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            height: 55px;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: #fff;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .btn {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.75rem 2.5rem;
            font-size: 1.1rem;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            font-weight: 500;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            color: white;
        }

        .form-control::-webkit-outer-spin-button,
        .form-control::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .form-control[type=number] {
            -moz-appearance: textfield;
        }

        @media (max-width: 768px) {
            .booking-form--wrapper {
                margin: 1rem;
                padding: 1.5rem;
            }

            .booking-form--inner h2 {
                font-size: 1.8rem;
            }
        }

        /* Background map styling */
        .booking-form--wrapper[data-background] {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .booking-form--wrapper[data-background]::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.92);
            z-index: 0;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="booking-form--wrapper" data-background="assets/imgs/resources/booking-map.png">
            <form id="form" action="pay.php" method="POST">
                <div class="booking-form--inner">
                    <h2>Make Your Payment</h2>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 mb-4">
                            <div class="form-group">
                                <input id="name" type="text" name="name" class="form-control" placeholder="Full Name*" required="">
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 mb-4">
                            <div class="form-group">
                                <input id="email" type="email" name="email" class="form-control" placeholder="Email*" required="">
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 mb-4">
                            <div class="form-group">
                                <input id="phone" type="tel" name="contact" class="form-control" placeholder="Contact Number*" required="">
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 mb-4">
                            <div class="form-group">
                                <input id="amount" type="number" name="amount" class="form-control" placeholder="Amount*" required="" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 mt-3">
                            <div class="booking--button text-center">
                                <input class="btn" type="submit" value="Proceed to Payment" name="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>