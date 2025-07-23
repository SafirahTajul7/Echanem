<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: home.php');
        exit();
    }

    include('../../incl/db_conn.php');

    $customer_id = $_SESSION['user_id'];
    $delivery_method = $_POST['delivery_method'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];
    $items = json_decode($_POST['items'], true);
    $delivery_cost = $_POST['delivery_cost'];
    $address = mysqli_real_escape_string($dbc, $_POST['address']);

    // Create order in the database (this is a simplified example)
    $query = "INSERT INTO orders (user_id, total, delivery_method, payment_method, delivery_cost, address, status) VALUES ('$customer_id', '$total_amount', '$delivery_method', '$payment_method', '$delivery_cost', '$address', 'Pending')";
    $result = mysqli_query($dbc, $query);

    if (!$result) {
        die('Error creating order: ' . mysqli_error($dbc));
    }

    $order_id = mysqli_insert_id($dbc);

    // Insert order items (this is a simplified example)
    foreach ($items as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $size = $item['size'];
        
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES ('$order_id', '$product_id', '$quantity', '$price', '$size')";
        $result = mysqli_query($dbc, $query);

        if (!$result) {
            die('Error creating order item: ' . mysqli_error($dbc));
        }
    }

    // Redirect to the selected payment method page
    if ($payment_method == 'PayPal') {
        header("Location: ../payment_paypal.php?order_id=$order_id");
        exit();
    } elseif ($payment_method == 'Credit Card') {
        header("Location: ../payment_ccard.php?order_id=$order_id");
        exit();
    } elseif ($payment_method == 'Bank Transfer') {
        header("Location: ../payment_banktrans.php?order_id=$order_id");
        exit();
    }

    // Close the database connection
    mysqli_close($dbc);
?>
