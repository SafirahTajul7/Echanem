<?php
    require('../../incl/db_conn.php');
    
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../access/login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = mysqli_real_escape_string($dbc, $_POST['product_id']);
    $size = mysqli_real_escape_string($dbc, $_POST['size']);

    // Check if the product is already in the cart with the same size
    $query = "SELECT * FROM carts WHERE user_id = $user_id AND product_id = $product_id AND size = '$size'";
    $res = mysqli_query($dbc, $query);

    if (mysqli_num_rows($res) > 0) {
        // If product is already in cart, update the quantity
        $query = "UPDATE carts SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id AND size = '$size'";
    } else {
        // If product is not in cart, add it to the cart
        $query = "INSERT INTO carts (user_id, product_id, quantity, size) VALUES ($user_id, $product_id, 1, '$size')";
    }

    if (mysqli_query($dbc, $query)) {
        echo json_encode(['success' => true, 'message' => 'Product added to cart successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($dbc)]);
    }

    mysqli_close($dbc);
?>
