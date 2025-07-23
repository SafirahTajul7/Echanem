<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../access/login.php');
        exit();
    }

    require('../../incl/db_conn.php');

    if (isset($_GET['order_id'])) {
        $order_id = (int)$_GET['order_id'];
        $user_id = $_SESSION['user_id'];

        // Check if the order belongs to the user and is pending
        $check_order_query = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id AND status = 'Pending'";
        $check_order_result = mysqli_query($dbc, $check_order_query);

        if (mysqli_num_rows($check_order_result) > 0) {
            // Update the order status to completed
            $update_order_query = "UPDATE orders SET status = 'Completed' WHERE id = $order_id";
            if (mysqli_query($dbc, $update_order_query)) {
                header('Location: ../profile.php');
                exit();
            } else {
                echo '<script>alert(\'Error updating order status..\'); window.location.href=\'../profile.php\';</script>';
            }
        } else {
            echo '<script>alert(\'Invalid order or order already completed.\'); window.location.href=\'../profile.php\';</script>';
        }
    } else {
        echo '<script>alert(\'Order ID not provided.\'); window.location.href=\'../profile.php\';</script>';
    }

    mysqli_close($dbc);
?>
