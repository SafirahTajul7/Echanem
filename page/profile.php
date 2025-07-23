<?php
    $page_title = 'User Profile | Echanem';
    include('../incl/web_title_alt.html');

    session_start();
    $greet = '';

    if (isset($_SESSION['user_id'])) {
        $cus_name = $_SESSION['user_name'];
        $greet = "Hello, " . $cus_name;
        $profile_button = '<a href="profile.php"><i class="fas fa-user"></i> My Profile</a>';
        $log_button = '<a href="../access/logout.php" onclick="return confirm(\'Are you sure you want to logout?\')"><i class="fas fa-sign-out"></i> Logout</a>';
    } else {
        header('Location: ../access/login.php');
        exit();
    }

    // Define the query to retrieve user data from customers table
    $user_id = $_SESSION['user_id'];
    $q = "SELECT * FROM customers WHERE id = $user_id";
    $r = mysqli_query($dbc, $q);

    if ($r) {
        $user = mysqli_fetch_assoc($r);
    } else {
        echo '<p class="error">Error retrieving user data.</p>';
        exit();
    }

    // Define the query to retrieve the user's orders from orders table
    $order_query = "SELECT * FROM orders WHERE user_id = $user_id";
    $order_result = mysqli_query($dbc, $order_query);

    include("../incl/header.html");
    ?>

    <body style="margin-top: 12.5vh;">
        <div class="title">
            <p>Echanem&#8482 Family <span>PROFILE</span></p>
            <hr>
        </div>
        <div class="damn">
            <div class="profile-info">
                <h2><?php echo $greet; ?></h2>
                <ul>
                    <li><strong>First Name:</strong> <?php echo htmlspecialchars($user['firstname']); ?></li>
                    <li><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lastname']); ?></li>
                    <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                    <li><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></li>
                    <li><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></li>
                    <li><strong>Account Created At:</strong> <?php echo htmlspecialchars($user['created_at']); ?></li>
                </ul>
                <div class="update-button">
                    <a href="update_profile.php">Update Profile</a>
                    <a href="../access/resetpasswd.php">Reset Password</a>
                </div>
            </div>

            <div class="orders">
                <h2>Your Orders</h2>
                <div class="order-section">
                    <div class="order-title" style="background-color: orange;">
                        <h3>Pending Orders</h3>
                    </div>
                    <?php
                    if ($order_result) {
                        $has_pending_orders = false;
                        mysqli_data_seek($order_result, 0);
                        while ($order = mysqli_fetch_assoc($order_result)) {
                            if ($order['status'] === 'Pending') {
                                $has_pending_orders = true;
                                echo '<div class="order">';
                                echo '<p><strong>Order ID:</strong> ' . htmlspecialchars($order['id']) . '</p>';
                                echo '<p><strong>Total:</strong> RM' . htmlspecialchars($order['total']) . '</p>';
                                echo '<p><strong>Status:</strong> ' . htmlspecialchars($order['status']) . '</p>';
                                echo '<p><strong>Created At:</strong> ' . htmlspecialchars($order['created_at']) . '</p>';
                                echo '<button class="receive-item-button" onclick="completeOrder(' . htmlspecialchars($order['id']) . ')">Receive Item</button>';
                    
                                // Fetch order items
                                $order_id = $order['id'];
                                $order_items_query = "SELECT * FROM order_items WHERE order_id = $order_id";
                                $order_items_result = mysqli_query($dbc, $order_items_query);
                    
                                if ($order_items_result) {
                                    echo '<div class="order-items">';
                                    echo '<ul>';
                                    while ($item = mysqli_fetch_assoc($order_items_result)) {
                                        $item_id = $item['product_id'];
                    
                                        // Fetch product name
                                        $product_name_query = "SELECT name FROM products WHERE id = $item_id";
                                        $product_name_result = mysqli_query($dbc, $product_name_query);
                    
                                        if ($product_name_result) {
                                            $prod = mysqli_fetch_assoc($product_name_result);
                                            $product_name = htmlspecialchars($prod['name']);
                                        } else {
                                            $product_name = 'Unknown';
                                        }
                    
                                        echo '<li>';
                                        echo '<strong>Product Name:</strong> ' . $product_name . '<br>';
                                        echo '<strong>Product ID:</strong> ' . htmlspecialchars($item['product_id']) . '<br>';
                                        echo '<strong>Quantity:</strong> ' . htmlspecialchars($item['quantity']) . '<br>';
                                        echo '<strong>Price (RM):</strong> ' . htmlspecialchars($item['price']) . '<br>';
                                        echo '<strong>Size:</strong> ' . htmlspecialchars($item['size']);
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                    echo '</div>';
                                } else {
                                    echo '<p class="error">Error retrieving order items.</p>';
                                }
                                echo '</div>';
                                echo '<hr>';
                            }
                        }
                        if (!$has_pending_orders) {
                            echo '<p>No pending orders.</p>';
                        }
                    } else {
                        echo '<p class="error">Error retrieving orders.</p>';
                    }                    
                    ?>
                </div>

                <div class="order-section">
                    <div class="order-title" style="background-color: green;">
                        <h3>Completed Orders</h3>
                    </div>
                    <?php
                    if ($order_result) {
                        $has_completed_orders = false;
                        mysqli_data_seek($order_result, 0);
                        while ($order = mysqli_fetch_assoc($order_result)) {
                            if ($order['status'] === 'Completed') {
                                $has_completed_orders = true;
                                echo '<div class="order">';
                                echo '<p><strong>Order ID:</strong> ' . htmlspecialchars($order['id']) . '</p>';
                                echo '<p><strong>Total: </strong>RM' . htmlspecialchars($order['total']) . '</p>';
                                echo '<p><strong>Status:</strong> ' . htmlspecialchars($order['status']) . '</p>';
                                echo '<p><strong>Created At:</strong> ' . htmlspecialchars($order['created_at']) . '</p>';

                                // Fetch order items
                                $order_id = $order['id'];
                                $order_items_query = "SELECT * FROM order_items WHERE order_id = $order_id";
                                $order_items_result = mysqli_query($dbc, $order_items_query);

                                if ($order_items_result) {
                                    echo '<div class="order-items">';
                                    echo '<ul>';
                                    while ($item = mysqli_fetch_assoc($order_items_result)) {
                                        $item_id = $item['product_id'];

                                        $product_name_query = "SELECT name FROM products WHERE id = $item_id";
                                        $product_name_result = mysqli_query($dbc, $product_name_query);

                                        if ($product_name_result) {
                                            $prod = mysqli_fetch_assoc($product_name_result);
                                            $product_name = htmlspecialchars($prod['name']);
                                        } else {
                                            $product_name = 'Unknown';
                                        }

                                        echo '<li>';
                                        echo '<strong>Product Name:</strong> ' . $product_name . '<br>';
                                        echo '<strong>Product ID:</strong> ' . htmlspecialchars($item['product_id']) . '<br>';
                                        echo '<strong>Quantity:</strong> ' . htmlspecialchars($item['quantity']) . '<br>';
                                        echo '<strong>Price (RM):</strong> ' . htmlspecialchars($item['price']) . '<br>';
                                        echo '<strong>Size:</strong> ' . htmlspecialchars($item['size']);
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                    echo '</div>';
                                } else {
                                    echo '<p class="error">Error retrieving order items.</p>';
                                }
                                echo '</div>';
                                echo '<hr>';
                            }
                        }
                        if (!$has_completed_orders) {
                            echo '<p>No completed orders.</p>';
                        }
                    } else {
                        echo '<p class="error">Error retrieving orders.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>

    <script>
        function completeOrder(orderId) {
            if (confirm('Are you sure you want to mark this order as received?')) {
                window.location.href = 'handler/cus_order_complete.php?order_id=' + orderId;
            }
        }
    </script>

    <script src="../incl/skrip.js"></script>
</html>

<?php
mysqli_close($dbc);
?>
