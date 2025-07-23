<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../home.php');
        exit();
    }

    $order_id = $_GET['order_id'];
    include('../../incl/db_conn.php');

    // Update order status
    $query = "UPDATE orders SET status = 'Pending' WHERE id = '$order_id'";
    $result = mysqli_query($dbc, $query);

    if (!$result) {
        die('Error updating order status: ' . mysqli_error($dbc));
    }

    mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/stail-success-banktrans.css">
        <link href='https://fonts.googleapis.com/css?family=Palanquin' rel='stylesheet'>
        <title>Payment Success - Bank Transfer</title>
        <link rel="icon" type="image/x-icon" href="../../resources/logo_echanem.png">
    </head>
    <body>
        <main>
            <div class="container">
                <div class="header">
                    <img src="../images/abc_bank.png" alt="Bank Transfer Logo">
                    <h1>Payment Successful</h1>
                    <p>Thank you for your payment!</p>
                </div>
                <div class="details">
                    <p>Order ID: <?php echo $order_id; ?></p>
                    <p>You have successfully completed the bank transfer.</p>
                    <p>We have received your payment and your order is now being processed.</p>
                </div>
                <div class="footer">
                    <a href="../order_summary.php?order_id=<?php echo $order_id; ?>" class="button">View Order Summary</a>
                </div>
            </div>
        </main>
    </body>
</html>
