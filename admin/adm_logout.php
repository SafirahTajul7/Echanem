<?php
    session_start();

    // As usual, check if the user is logged in
    // If admin not logged in, go back to login page
    if (!isset($_SESSION['admin_id'])) {
        header('Location: adm_login.php');
        exit();
    } 

    else { // Cancel the session:
        $_SESSION = array(); // Clear the variables.
        session_destroy(); // Destroy the session itself.
        setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); // Destroy the cookie.
    }

    header('Location: adm_login.php');
?>