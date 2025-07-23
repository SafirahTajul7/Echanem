<?php
    $page_title = 'Admin | Echanem';
    include ('../incl/web_title.html');

    session_start();

    $adm_name = $_SESSION['admin_name'];

    if (!isset($_SESSION['admin_id'])) {
        header('Location: adm_login.php');
        exit();
    }

    // Handle edit form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
        $edit_id = mysqli_real_escape_string($dbc, $_POST['edit_id']);
        $fname = mysqli_real_escape_string($dbc, $_POST['firstname']);
        $lname = mysqli_real_escape_string($dbc, $_POST['lastname']);
        $email = mysqli_real_escape_string($dbc, $_POST['email']);
        $phone = mysqli_real_escape_string($dbc, $_POST['phone']);
        $address= mysqli_real_escape_string($dbc, $_POST['address']);

        $q = "UPDATE `customers` SET firstname = '$fname', lastname = '$lname', email = '$email', phone = '$phone', address = '$address' WHERE id = '$edit_id'";
        $r = mysqli_query($dbc, $q);
        if ($r) {
            echo '<script>alert("User info updated successfully.");</script>';
        } else {
            echo '<script>alert("Error updating user/customer info: ' . mysqli_error($dbc) . '");</script>';
        }
    }

    // Define the query to retrieve data from `customers` table
    $q = "SELECT `id`, `firstname`, `lastname`, `email`, `phone`, `address`, DATE_FORMAT(`created_at`, '%M %d, %Y') AS `date_joined` FROM `customers` ORDER BY `created_at` ASC";
    $r = mysqli_query($dbc, $q);

    // Count the number of returned rows
    $user_num = mysqli_num_rows($r);
?>

    <body>
        <div class="cont">
            <?php
                // Include sidebar
                include 'adm_navbar.php';
            ?>
            <main>
                <div class="main-head">
                    <span class="material-symbols-outlined">group</span>
                    <p>View, edit, and manage customer accounts on this platform</p>
                    <h5>Manage Users</h5>
                </div>
                <div class="main-content">
                    <div class="content-cont">
                        <div id="user-list">
                            <h3>👥 Registered Customer List</h3>
                            <?php
                            if ($user_num > 0) { // If it ran OK, display the records
                                echo "<p>There are currently $user_num registered customers.</p>";
                                echo '<div class="table-rx"><table class="table-dsgn">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Date Joined</th>
                                        <th>Edit Info</th>
                                        <th>Delete User</th>
                                    </tr>
                                </thead>
                                <tbody>';

                                while ($res = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                    echo '<tr>
                                        <td>' . htmlspecialchars($res['firstname']) . '</td>
                                        <td>' . htmlspecialchars($res['lastname']) . '</td>
                                        <td>' . htmlspecialchars($res['email']) . '</td>
                                        <td>' . htmlspecialchars($res['phone']) . '</td>
                                        <td>' . htmlspecialchars($res['address']) . '</td>
                                        <td>' . htmlspecialchars($res['date_joined']) . '</td>
                                        <td><a href="javascript:void(0);" class="edit-btn" onclick="editMember(
                                            \'' . $res['id'] . '\', 
                                            \'' . htmlspecialchars($res['firstname'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['lastname'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['email'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['phone'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars(addslashes($res['address']), ENT_QUOTES) . '\'
                                        )">EDIT</a></td>
                                        <td><form action="" method="post" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this user?\');">
                                            <input type="hidden" name="del_id" value="' . $res['id'] . '">
                                            <button type="submit" class="delete-btn">DELETE</button>
                                        </form></td>
                                    </tr>';
                                }

                                echo '</tbody></table></div>';
                                mysqli_free_result($r); // Free memory associated with $r    

                            } else { // If no records were returned
                                echo '<p class="error">There are currently no registered customers.</p>';
                            }

                            // Have to delete order and order items

                            if (isset($_POST['del_id'])) {
                                $del_id = $_POST['del_id'];
                                $q = "DELETE FROM `customers` WHERE `id` = '$del_id'";
                                $r = mysqli_query($dbc, $q);
                                if ($r) { 
                                    echo '<script>alert("User deleted successfully."); window.location.href="adm_users.php"</script>';
                                } 
                                else { echo '<script>alert("Error deleting user: ' . mysqli_error($dbc) . '");</script>'; }
                            }

                            mysqli_close($dbc); // Close the database connection
                            ?>
                        </div>

                        <div id="user-update">
                            <h2>✍️ Edit User Account Information</h2>
                            <form action="adm_users.php" method="post">
                                <input type="hidden" id="edit_id" name="edit_id"/>

                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" id="firstname" name="firstname"/>
                                </div>

                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" id="lastname" name="lastname"/>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email"/>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" id="phone" name="phone" maxlength="20"/>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" rows="4"></textarea>
                                </div>

                                <input type="submit" id="update_button" name="submit" value="UPDATE INFO"/>
                            </form> 
                        </div>
                    </div>
                </div>     
            </main>
        </div>
    </body>
    <script src="../incl/skrip.js"></script>
</html>
