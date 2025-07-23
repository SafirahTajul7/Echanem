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
        <link rel="icon" type="image/x-icon" href="https://www.paypalobjects.com/webstatic/icon/pp258.png">
        <link rel="stylesheet" href="../css/stail-paypal.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <title>PayPal Payment</title>
    </head>
    <body>
        <main>
            <div class="container">
                <div style="flex: 1;">
                    <div class="header">
                        <img src="images/paypal_logo_header.png" alt="PayPal Logo">
                        <span class="amount"><i class="fa fa-shopping-cart"></i> RM <?php echo $order_price ?> MYR</span>
                    </div>
                    <hr>
                    <div class="welcome">
                        <?php echo 'Welcome back, ' . $_SESSION['user_name'] . '!'; ?>
                    </div>
                    <div class="payment-method">
                        Pay with
                        <div>
                            <strong><img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal Icon">PayPal Balance</strong>
                            RM2000.00
                        </div>
                        <div><b></b><span>MYR</span></div>
                    </div>
                    <div class="credit-option">
                        <div style="flex: 7;">
                            <b>Get more time to pay with PayPal Credit</b></br></br>
                            PayPal Credit is a credit line available almost anywhere PayPal is accepted. Subject to credit approval. <a href="#">See Terms</a>
                        <br>
                        </div>
                        <div style="flex: 3; text-align: center;">
                            <a href="#">Apply Now</a>
                        </div>
                    </div>
                    <a href="handler/cus_paysucc_paypal.php?order_id=<?php echo $order_id; ?>" class="pay-now">Pay Now</a>
                </div>
                <div class="center">
                    <div class="credit-deco">
                        <div>
                            <img src="images/paypal_protect.png" alt="PayPal Logo">
                            <h3>PayPal is the safer,</br> easier way to pay</h3>
                            No matter where you shop, we keep your financial information secure.
                        <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <span>
                    <a href="#">Policies</a> &ensp; 
                    <a href="#">Terms</a> &ensp; 
                    <a href="#">Privacy</a> &ensp; 
                    <a href="#">Feedback</a> &ensp; 
                </span>
                <span>
                    <a>&#169; 1999 - 2024 <i class="fa fa-lock"></i></a>
                </span>
            </div>
        </main>
    </body>
</html>
