<?php
    $page_title = 'Sign Up | Echanem';
    include('../incl/web_title.html');
    ?>

    <body>
        <div class="scrn">
            <div id="pg_deco">
                <div class="slaido">
                    <div></div>
                </div>
            </div>

            <div id="pg_enter">
                <div id="sign-up">
                    <div class="title">
                        <h2>Join 1.8 MILLION shoppers</h2>
                        <p>Already have an account? <a href="login.php">Sign in</a></p>
                    </div>
                    <!-- Setup Form later for backend work.. -->
                    <form action="registered_success.php" method="post" onsubmit="return validatePassword()">
                        <p>Unlock Exclusive Offers & Join the <span><b>Echanem&#8482 Family</b></span>. Discover your new favorite outfit soon!</p>
                        <div style="display: flex;">
                            <div class="inIco">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="fname" placeholder="First Name" required>
                            </div>
                            <span style="width: 16px;"></span>
                            <div class="inIco">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" name="lname" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" placeholder="Email Address" required>
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-phone"></i>
                            <input type="text" name="phone" placeholder="Phone Number" required>
                        </div>
                        <div class="inIco">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <span class="tog-passwd" onclick="passwdVisibility('password','.tog-passwd')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                        <div id="password-error" style="color: red; display: none;">Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.</div>
                        <div style="display: flex; align-items: center;">
                            <input type="checkbox" name="agreement" id="chckOn" onchange="document.getElementById('reg-btn').disabled = !this.checked;">
                            <p>I agree to the <a href="">Terms of Service</a> & <a href="">Privacy Policy</a>.</p>
                        </div>
                        <input type="submit" name="register" id="reg-btn" value="GET STARTED" disabled>
                    </form>
                </div>

                <div id="brand-cp">
                    <img src="../resources/logo_echanem.png" alt="Echanem logo" height="48">
                    <p>Copyright &#169 2024 Echanem Ltd. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
    <script src="../incl/skrip.js"></script>
</html>