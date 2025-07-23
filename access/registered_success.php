<?php
$page_title = 'Registration Success | Echanem';
include('../incl/web_title.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        $fname = mysqli_real_escape_string($dbc, $_POST['fname']);
        $lname = mysqli_real_escape_string($dbc, $_POST['lname']);
        $email = mysqli_real_escape_string($dbc, $_POST['email']);
        $phone = mysqli_real_escape_string($dbc, $_POST['phone']); // Escape the phone input as well
        $password = mysqli_real_escape_string($dbc, $_POST['password']);

        // Validate phone number
        if (!preg_match('/^[0-9]+$/', $phone)) {
            throw new Exception('Phone number must contain only numbers.');
        }

        // Hash the password
        $hpasswd = hash('sha512', $password);

        // Insert the user into the database
        $query = "INSERT INTO customers (firstname, lastname, email, phone, password) VALUES ('$fname', '$lname', '$email', '$phone', '$hpasswd')";
        $result = mysqli_query($dbc, $query);

        if (!$result) {
            throw new Exception('Error registering user: ' . mysqli_error($dbc));
        }

        // Display success page
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $page_title; ?></title>
            <link rel="stylesheet" href="../incl/style.css"> <!-- Adjust the path to your CSS file -->
            <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Adjust the path to FontAwesome -->
        </head>
        <body>
            <div class="scrn">
                <div id="pg_deco">
                    <div class="slaido">
                        <div></div>
                    </div>
                </div>
                
                <div id="pg_enter">
                    <div id="registered">
                        <div class="title">
                            <h2 style="color: #7000FF;">Congrats! &#127881;</h2>
                            <h3 style="margin: 10px 0 15px 0;">You're now part of the <span><b>Echanem&#8482 Family</b></span>.</h3>
                            <p>Thank you for registering with <b>Echanem&#8482</b>. To start shopping and unlock exclusive benefits, please <a href="login.php">sign in</a> to your new account.</p>
                            </br>
                            <p>So, what are benefits of being a member of our Echanem&#8482 Family?</p>
                            
                            <ul class="benefits-list">
                                <li>
                                    <i class="fa-solid fa-heart"></i> Create wishlists and save items for later purchases.
                                </li>
                                <li>
                                    <i class="fa-solid fa-rocket"></i> No need to re-enter details every time you shop.
                                </li>
                                <li>
                                    <i class="fa-solid fa-box"></i> View past purchases and manage your order history.
                                </li>
                                <li>
                                    <i class="fa-solid fa-diamond"></i> Get early access to sales, promotions, and new arrivals.
                                </li>
                                <li>
                                    <i class="fa-solid fa-star"></i> Discover styles curated just for you based on your preferences.
                                </li>
                            </ul>
                        </div>
                        <div style="margin-top: 16px;" align="right">
                            <a href="login.php" id="small-btn">SIGN IN NOW</a></p>
                        </div>
                    </div>
                    
                    <div id="brand-cp">
                        <img src="../resources/logo_echanem.png" alt="Echanem logo" height="48">
                        <p>Copyright &#169 2024 Echanem Ltd. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>

        <?php

    } catch (Exception $ex) {
        // Show error message
        echo '<script>alert("' . $ex->getMessage() . '"); window.history.back();</script>';
    } finally {
        mysqli_close($dbc);
    }
} else {
    echo '<script>alert("Invalid request method."); window.history.back();</script>';
}
?>
