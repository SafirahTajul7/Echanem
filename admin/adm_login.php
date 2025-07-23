<?php
    $page_title = 'Admin | Echanem';
    include ('../incl/web_title.html');

    session_start();
    $error_msg = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['log-adm-password'];

        // Fetch admin from database
        $query = "SELECT * FROM admin WHERE username = ?";
        $stmt = $dbc->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        // Anything just play around here... 
        // https://10015.io/tools/sha512-encrypt-decrypt
        $hpasswd = hash('sha512', $password);

        if ($admin && $hpasswd === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: adm_dashboard.php');
        } else {
            $error_msg = '
                <div class="error_msg" style="padding: 16px;">
                    <p><strong>Error!</strong> Invalid username or password.</p>
                </div>
            ';
        }

        $stmt->close();
        $dbc->close();
    }
?>

    <body>
        <?php
         	if(isset($success_msg)) {
				echo '<script>' . 'alert("' . $success_msg . '");' . '</script>';
			}
		?>
        <div class="scrn">
            <div class="imghold" style="background-image: url('../resources/adm.jpg');">
                <div id="pg_enter" class="blurr">
                    <div id="login">
                        <div class="title">
                            <div style="display: flex; justify-content: center;">
                                <img src="../resources/logo_big_echanem.png" alt="Echanem logo" height="128">
                            </div>
                            <?php 
                                if (!empty($error_msg))
                                    echo $error_msg; 
                            ?>
                            <p>Selamat Datang, <b>Admin</b>.</p>
                        </div>

                        <!-- Setup Form later for backend work.. -->
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                            <div style="display: flex;">
                            </div>
                            <div class="inIco">
                                <i class="fa-solid fa-user-alt"></i>
                                <input type="text" name="username" placeholder="Username" required>
                            </div>
                            <div class="inIco">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="log-adm-password" id="password" placeholder="Password" required>
                                <span class="tog-passwd" onclick="passwdVisibility('password','.tog-passwd')"><i class="fa-solid fa-eye"></i></span>
                            </div>
                            <input type="submit" name="login" id="log-adm-btn" style="background-color: #D70040; margin-top: 16px;" value="ENTER">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="../incl/skrip.js"></script>
</html>