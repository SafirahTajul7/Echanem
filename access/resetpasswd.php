<?php
    $page_title = 'Reset Password | Echanem';
    include ('../incl/web_title.html');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$email = $_POST['get-email'];
		$oldPassword = $_POST['old-password'];
		$newPassword = $_POST['new-password'];

		if (strlen($newPassword) < 8) {
			$error_msg = "Your new password must be at least 8 characters or more.";
		} else {
			$q = "SELECT * FROM `customers` WHERE `email` = '$email'";
			$res = mysqli_query($dbc, $q);

			if (!$res) {
				die('Query failed: ' . mysqli_error($dbc));
			}

			if(mysqli_num_rows($res) == 1) {
				$r = mysqli_fetch_assoc($res);
				$hpass = $r['password'];

                // Anything just play around here... 
                // https://10015.io/tools/sha512-encrypt-decrypt
				$hpass_old = hash('sha512', $oldPassword);

				if($hpass === $hpass_old) {
						$hpass_new = hash('sha512', $newPassword);

						if($hpass_new == $hpass_old) {
							$error_msg = "New password cannot be same with old password. Please enter other new password value.";
						} else {
							$update_q = "UPDATE `customers` SET `password` = '$hpass_new' WHERE `email` = '$email'";
							
							if(mysqli_query($dbc, $update_q)) {
								$success_msg = "Your password has been updated successfully. You may login now.";
    							header('Location: login.php?success_msg=' . urlencode($success_msg));
            					exit;
							} else {
								$error_msg = "Failed to update your password. Please try again.";
							}
						}
				} else {
					$error_msg = "Your old password is incorrect.";
				}
		    } else {
				$error_msg = "The email address was not found. Please try again.";
			}
		}
	}
?>

    <body>
        <div class="scrn">
            <div id="pg_enter">
                <div id="login">
                    <?php
                        if (isset($error_msg)) {
                            echo '<script>' . 'alert("' . $error_msg . '");' . '</script>';
                        }
                        if (isset($success_msg)) {
                            echo  '<script>' . 'alert("' . $success_msg . '");' . '</script>';
                        }
                    ?>

                    <div style="display: inline-block; margin-bottom: 12px;">
                        <a href="login.php" class="linker">
                            <i class="fa fa-angle-left" style="color: #7000FF;"></i>
                            Back to Sign in
                        </a>
                    </div>
                    <div class="title">
                        <h2>Reset Your <span style="color: #7000FF;">Password</span>.</h2>
                        <p>To change to change your password, please fill in the fields below. Your password must contain at least 8 characters.</p>
                    </div>

                    <!-- Setup Form later for backend work.. -->
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                        <div style="display: flex;">
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-user-alt"></i>
                            <input type="email" name="get-email" placeholder="Enter your email" required>
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="old-password" id="password" placeholder="Old password" required>
                            <span class="tog-passwd" onclick="passwdVisibility('password','.tog-passwd')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="new-password" id="password2" placeholder="New Password" required>
                            <span class="tog-passwd2" onclick="passwdVisibility('password2','.tog-passwd2')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                            <input type="submit" name="login" id="rpw-btn" value="RESET PASSWORD">
                    </form>
                </div>
                
                <div id="brand-cp">
                    <img src="../resources/logo_echanem.png" alt="Echanem logo" height="48">
                    <p>Copyright &#169 2024 Echanem Ltd. All rights reserved.</p>
                </div>
            </div>

            <div id="pg_deco">
                <div class="imghold" style="background-image: url('../resources/newpass.jpg');">
                    <div class="ovlay"></div>
                </div>
            </div>
        </div>
    </body>
    <script src="../incl/skrip.js"></script>
</html>