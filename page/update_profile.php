<?php
    $page_title = 'Update Profile | Echanem';
    include('../incl/web_title_alt.html');

    session_start();
    if (isset($_SESSION['user_id'])) {
        $cus_name = $_SESSION['user_name'];
        $greet = "Hello, " . $cus_name;
        $profile_button = '<a href="profile.php"><i class="fas fa-user"></i> My Profile</a>';
        $log_button = '<a href="../access/logout.php" onclick="return confirm(\'Are you sure you want to logout?\')"><i class="fas fa-sign-out"></i> Logout</a>';
    } else {
        header('Location: ../access/login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $q = "UPDATE `customers` SET 
            `firstname` = '$firstname', 
            `lastname` = '$lastname', 
            `email` = '$email', 
            `phone` = '$phone', 
            `address` = '$address' 
            WHERE `id` = $user_id";

        $r = mysqli_query($dbc, $q);
        if ($r) {
            echo '<p class="success">Profile updated successfully.</p>';
        } else {
            echo '<p class="error">Error updating profile.</p>';
        }
    }

    // Retrieve current user data
    $q = "SELECT * FROM `customers` WHERE `id` = $user_id";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        $user = mysqli_fetch_assoc($r);
    } else {
        echo '<p class="error">Error retrieving user data.</p>';
        exit();
    }

    include("../incl/header.html");
    ?>

    <body style="margin-top: 12.5vh;">
        <div class="title">
            <p>Echanem&#8482 <span>UPDATE PROFILE</span></p>
            <hr>
        </div>
        <div class="damner">
            <div class="update-form">
                <form action="update_profile.php" method="POST">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                    
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                    
                    <label for="email">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    
                    <label for="address">Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                    <div style="display: flex; justify-content: space-between;">
                        <button onclick="history.back()" style="background-color: #007bff;">BACK</button>
                        <button class="grad-fx-plus" type="submit">UPDATE PROFILE</button>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <script src="../incl/skrip.js"></script>

</html>