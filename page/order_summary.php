<?php
    $page_title = 'Order Summary | Echanem';
    include('../incl/web_title_alt.html');

    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../access/login.php');
        exit();
    } else {
        $cus_name = $_SESSION['user_name'];
        $greet = "Hello, " . $cus_name;
        $profile_button = '<a href="profile.php"><i class="fas fa-user"></i> My Profile</a>';
        $log_button = '<a href="../access/logout.php" onclick="return confirm(\'Are you sure you want to logout?\')"><i class="fas fa-sign-out"></i> Logout</a>';
    }

    $order_id = mysqli_real_escape_string($dbc, $_GET['order_id']);

    // Fetch order details
    $query = "SELECT o.id, o.total AS total, o.status AS status, o.created_at, o.delivery_method, o.delivery_cost, o.payment_method, c.firstname, c.lastname, c.address
            FROM orders o
            JOIN customers c ON o.user_id = c.id
            WHERE o.id = '$order_id'";
    $result = mysqli_query($dbc, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die('Error fetching order details: ' . mysqli_error($dbc));
    }

    $order = mysqli_fetch_assoc($result);

    // Fetch order items
    $query = "SELECT oi.product_id, oi.quantity, oi.price, oi.size, p.name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = '$order_id'";
    $result = mysqli_query($dbc, $query);

    if (!$result) {
        die('Error fetching order items: ' . mysqli_error($dbc));
    }

    $order_items = [];
    while ($item = mysqli_fetch_assoc($result)) {
        $order_items[] = $item;
    }

    include("../incl/header.html");
    ?>
    <body>
        <div class="order-summary">
            <h1><i class="fas fa-receipt"></i> Order Summary</h1>
            <h2>Order Details</h2>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['firstname'] . ' ' . $order['lastname']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
            <p><strong>Delivery Method:</strong> <?php echo htmlspecialchars($order['delivery_method']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
            <br/>
            <h2>Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['size']); ?></td>
                            <td>RM <?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>RM <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>TOTAL (including delivery cost of RM <?php echo number_format($order['delivery_cost'], 2); ?>):</strong></td>
                        <td><strong>RM <?php echo number_format($order['total'], 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
            <button onclick="window.print()" class="grad-fx-plus"><i class="fas fa-print"></i> Print</button>
            <button type="button" onclick="window.location.href='catalogue.php'" class="grad-fx-plus"><i class="fa-solid fa-bag-shopping"></i> Continue Shopping</button>
        </div>
    </body>
</html>
<?php mysqli_close($dbc); ?>
