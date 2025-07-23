    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: home.php');
        exit();
    }

    $order_id = $_GET['order_id'];
    include('../incl/db_conn.php');

    // Fetch order details
    $query = "SELECT * FROM orders WHERE id = '$order_id'";
    $result = mysqli_query($dbc, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die('Error fetching order details: ' . mysqli_error($dbc));
    }

    $order = mysqli_fetch_assoc($result);
    $order_price = $order['total'];

    mysqli_close($dbc);
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Credit Card Payment</title>
        <link rel="icon" type="image/x-icon" href="../resources/logo_echanem.png">
        <link href='https://fonts.googleapis.com/css?family=Palanquin' rel='stylesheet'>
        <link rel="stylesheet" href="../css/stail-ccard.css">
        <style>
            .error-message {
                color: red;
                font-size: 12px;
                margin-bottom: 5px;
                display: none;
            }
        </style>
        <script>
            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.innerText = message;
                errorElement.style.display = 'block';
            }

            function clearErrors() {
                const errorElements = document.querySelectorAll('.error-message');
                errorElements.forEach(function(errorElement) {
                    errorElement.style.display = 'none';
                });
            }

            function validateForm() {
                clearErrors();
                let isValid = true;

                const cardNumber = document.getElementById('card-number').value.replace(/\s+/g, '');
                const cvv = document.getElementById('cvv').value;
                const expiryDate = document.getElementById('expiry-date').value;
                const cardName = document.getElementById('card-name').value;

                const cardNumberPattern = /^\d{16}$/;
                const cvvPattern = /^\d{3}$/;
                const expiryDatePattern = /^(0[1-9]|1[0-2])\/\d{2}$/;

                if (!cardNumberPattern.test(cardNumber)) {
                    showError('card-number-error', 'Please enter a valid 16-digit credit card number.');
                    isValid = false;
                }

                if (!cvvPattern.test(cvv)) {
                    showError('cvv-error', 'Please enter a valid 3-digit CVV code.');
                    isValid = false;
                }

                if (!expiryDatePattern.test(expiryDate)) {
                    showError('expiry-date-error', 'Please enter a valid expiry date in MM/YY format.');
                    isValid = false;
                }

                if (cardName.trim() === '') {
                    showError('card-name-error', 'Please enter the name on the card.');
                    isValid = false;
                }

                return isValid;
            }

            function formatCardNumber(event) {
                const input = event.target;
                const value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                const formattedValue = value.match(/.{1,4}/g)?.join(' ') ?? value;
                input.value = formattedValue;
            }

            document.addEventListener('DOMContentLoaded', function () {
                const cardNumberInput = document.getElementById('card-number');
                cardNumberInput.addEventListener('input', formatCardNumber);
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="product-section">
                <div class="product-row total-row">
                    <span>Total</span>
                    <span></span>
                    <span>RM <?php echo $order_price; ?></span>
                </div>
            </div>

            <div class="payment-section">
                <div class="payment-option">
                    <p>Safe money transfer with your bank account.<br>Visa, Master, Discover, Amex, JCB.</p>
                    <div class="card-icons">
                        <img src="images/visa.svg" alt="Visa">
                        <img src="images/mastercard.svg" alt="MasterCard">
                        <img src="images/discover.svg" alt="Discover">
                        <img src="images/amex.svg" alt="Amex">
                        <img src="images/jcb.svg" alt="JCB">
                    </div>
                </div>
                <form action="handler/cus_paysucc_ccard.php?order_id=<?php echo $order_id; ?>" method="POST" onsubmit="return validateForm()">
                    <div class="credit-card-form">
                        <div class="i1">
                            <label for="card-number">Credit Card Number</label></br>
                            <input type="text" id="card-number" name="card-number" placeholder="XXXX XXXX XXXX XXXX" required>
                            <div id="card-number-error" class="error-message"></div>
                        </div>
                        <div class="i2">
                            <label for="cvv">CVV Code</label></br>
                            <input type="text" id="cvv" name="cvv" placeholder="666" required>
                            <div id="cvv-error" class="error-message"></div>
                        </div>
                        <div class="i3">
                            <label for="expiry-date">Expiry Date</label></br>
                            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>
                            <div id="expiry-date-error" class="error-message"></div>
                        </div>
                        <div class="i4">
                            <label for="card-name">Name on Card</label></br>
                            <input type="text" id="card-name" name="card-name" placeholder="Your name registered on card" required>
                            <div id="card-name-error" class="error-message"></div>
                        </div>
                        <button type="submit" class="checkout-button i5">CHECK OUT</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>