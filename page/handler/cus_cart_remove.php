<?php
    require('../../incl/db_conn.php');

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../access/login.php');
        exit();
    }
    
    $cart_id = $_POST['cart_id'];

    $query = "DELETE FROM carts WHERE id = $cart_id";
    if (mysqli_query($dbc, $query)) {
        echo '<script>
                alert("Item removed from cart successfully.");
                window.location.href=\'../cart.php\';
            </script>';
    } else {
        echo '<script>
                alert("Error: "' .  mysqli_error($dbc) .'");
                window.location.href=\'../cart.php\';
            </script>';
    }

    mysqli_close($dbc);
    exit();
?>
