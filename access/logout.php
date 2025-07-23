<?php
    session_start();

    // As usual, check if the user is logged in
    // If customer not logged in, go back to login page
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    } 

    else { // Cancel the session:
        $_SESSION = array(); // Clear the variables.
        session_destroy(); // Destroy the session itself.
        setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); // Destroy the cookie.
    }

    header('Location: ../page/home.php');
?>