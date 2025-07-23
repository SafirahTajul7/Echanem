<?php
    require('../../incl/db_conn.php');

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../access/login.php');
        exit();
    }

    $cart_id = mysqli_real_escape_string($dbc, $_POST['cart_id']);
    $quantity = mysqli_real_escape_string($dbc, $_POST['quantity']);
    $size = mysqli_real_escape_string($dbc, $_POST['size']);

    $query = "UPDATE carts SET quantity = $quantity, size = '$size' WHERE id = $cart_id";
    if (mysqli_query($dbc, $query)) {
        echo '<script>
                alert("Cart updated successfully.");
                window.location.href=\'../cart.php\';
            </script>';
    } else {
        echo '<script>
                alert("Error: ' . mysqli_error($dbc) . '");
                window.location.href=\'../cart.php\';
            </script>';
    }

    mysqli_close($dbc);
    exit();
?>
