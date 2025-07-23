<?php
    $page_title = 'Sign In | Echanem';
    include ('../incl/web_title.html');

    session_start();
    $error_msg = '';

    if (isset($_SESSION['user_id'])) {
        header('Location: ../page/home.php');
        exit();
    }

    // This one for if password reset is successful (refer to resetpasswd.php)
    if(isset($_GET['success_msg'])) {
		$success_msg = htmlspecialchars($_GET['success_msg']);
	}

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = filter_var($_POST['log-email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['log-password'];
        
        $q = "SELECT * FROM customers WHERE email = ?";
        $stmt = $dbc->prepare($q);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1) {
            $customer = $result->fetch_assoc();

            // Anything just play around here... 
            // https://10015.io/tools/sha512-encrypt-decrypt
            $hpasswd = hash('sha512', $password);

            if ($hpasswd === $customer['password']) {
                $_SESSION['user_id'] = $customer['id'];
                $_SESSION['user_name'] = $customer['firstname'];
                header('Location: ../page/home.php');
                exit();
            } else {
                $error_msg = '
                    <div class="error_msg">
                        <p><strong>Error!</strong> Your password is incorrect.</p>
                    </div>
                ';
            }
        } else {
            $error_msg = '
                <div class="error_msg">
                    <p><strong>Error!</strong> Email not found. Please enter your registered email address.</p>
                </div>
            ';
        }
    }
?>

    <body>
        <?php
         	if(isset($success_msg)) {
				echo '<script>' . 'alert("' . $success_msg . '");' . '</script>';
			}
		?>
        <div class="scrn">
            <div id="pg_enter">
                <div id="login">
                    <div class="title">
                        <h2>Sign in to <span style="color: #7000FF;">Echanem</span>&#8482</h2>
                        <p>You need to <b>sign in</b> before continuing.</p>
                    </div>

                    <?php 
                        if (!empty($error_msg))
                            echo $error_msg; 
                    ?>

                    <!-- Setup Form later for backend work.. -->
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                        <div style="display: flex;">
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-user-alt"></i>
                            <input type="email" name="log-email" placeholder="Enter your email" required>
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="log-password" id="password" placeholder="Enter your password" required>
                            <span class="tog-passwd" onclick="passwdVisibility('password','.tog-passwd')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                        <div style="text-align: right; margin: -12px 0 4px 0;">
                            <p><i class="fa fa-info-circle"></i> Forgot your <a href="resetpasswd.php">Password</a>?</p>
                        </div>
                        <input type="submit" name="login" id="log-btn" value="SIGN IN">
                        <p>Need an account? <a href="signup.php">Register here</a></p>
                    </form>
                </div>
                
                <div id="brand-cp">
                    <img src="../resources/logo_echanem.png" alt="Echanem logo" height="48">
                    <p>Copyright &#169 2024 Echanem Ltd. All rights reserved.</p>
                </div>
            </div>

            <div id="pg_deco">
                <video autoplay muted loop id="comm-vid">
                    <source src="../resources/fashion.mp4" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
                <div class="ovlay ovlay-z"></div>
            </div>
        </div>
    </body>
    <script src="../incl/skrip.js"></script>
</html>