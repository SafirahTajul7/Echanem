<?php
    $page_title = 'Your Shopping Cart | Echanem';
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

    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    $query = "SELECT carts.id as cart_id, products.name, products.price, carts.quantity, carts.size, products.image_url, products.sizes
              FROM carts 
              JOIN products ON carts.product_id = products.id 
              WHERE carts.user_id = $user_id";
    $res = mysqli_query($dbc, $query);

    $total_price = 0;
    
    include("../incl/header.html");
?>
    <body style="margin-top: 10vh;">
        <section class="cart-section">
            <h1><i class="fa-solid fa-cart-plus" style="font-size: 1.75rem;"></i> YOUR SHOPPING CART</h1>
            </br>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $total_price += $row['price'] * $row['quantity'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width: 50px;"></td>
                                <td>RM <?php echo htmlspecialchars($row['price']); ?></td>
                                <td>
                                    <form action="handler/cus_cart_update.php" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                        <select name="size">
                                            <?php
                                            $sizes = json_decode($row['sizes']);
                                            foreach ($sizes as $size) {
                                                $selected = ($size == $row['size']) ? 'selected' : '';
                                                echo "<option value=\"$size\" $selected>$size</option>";
                                            }
                                            ?>
                                        </select>
                                </td>
                                <td>
                                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                                </td>
                                <td class="cart-actions">
                                        <button type="submit" id="cart-update">Update</button>
                                    </form>
                                    <form action="handler/cus_cart_remove.php" method="post" style="display: inline;">
                                        <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                        <button type="submit" id="cart-remove" onclick="return confirm('Are you sure you want to remove this item from your cart?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6">Your cart is empty.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="cart-total">
                <strong>Total Price: RM <?php echo $total_price; ?></strong>
            </div>
            <div class="cart-total">
                <button onclick="window.location.href='checkout.php'" class="grad-fx-plus">Checkout</button>
            </div>
        </section>
    </body>
    </html>
<?php
    mysqli_close($dbc);
?>
