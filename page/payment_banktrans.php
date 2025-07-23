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

    // Fetch user details
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM customers WHERE id = '$user_id'";
    $user_result = mysqli_query($dbc, $query);

    if (!$user_result || mysqli_num_rows($user_result) == 0) {
        die('Error fetching user details: ' . mysqli_error($dbc));
    }

    $user = mysqli_fetch_assoc($user_result);
    $user_name = $user['firstname'] . ' ' . $user['lastname'];

    mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="images/abc_bank.png">
        <link rel="stylesheet" href="../css/stail-banktrans.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <title>Bank Transfer Payment</title>
    </head>
    <body>
        <main>
            <div class="container">
                <div class="header">
                    <img src="images/abc_bank.png" alt="Bank Transfer Logo">
                    <span class="amount">MYR <?php echo $order_price ?></span>
                </div>
                <div class="welcome">
                    <?php echo 'Welcome back, ' . $_SESSION['user_name'] . '!'; ?>
                </div>
                <div class="payment-method">
                    <p><strong>Bank Account Details</strong></p>
                    <p><span><b>Account Name:</b></span> <span><?php echo $user_name; ?></span></p>
                    <p><span><b>Account Number:</b></span> <span>123456789</span></p>
                    <p><span><b>SWIFT Code:</b></span> <span>ABCD1234</span></p>
                </div>
                <div class="center">
                    <img src="images/bank_protection.png" alt="Bank Protection">
                    <h3>Bank Transfer is Safe and Secure</h3>
                    <p>Your payment information is safe with us.</p>
                    <a href="handler/cus_paysucc_banktrans.php?order_id=<?php echo $order_id; ?>" class="pay-now">I HAVE TRANSFERRED</a>
                </div>
                <div class="footer">
                    <span>
                        <a href="#">Policies</a> &ensp; 
                        <a href="#">Terms</a> &ensp; 
                        <a href="#">Privacy</a> &ensp; 
                    </span>
                    <span>
                        <a>&#169; 1959 - 2024 <i class="fa fa-lock"></i></a>
                    </span>
                </div>
            </div>
        </main>
    </body>
</html>
