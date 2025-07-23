<?php
    $page_title = 'Checkout | Echanem';
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

    $customer_id = $_SESSION['user_id'];
    $total = 0;
    $items = [];
    $address = '';

    if (isset($_GET['id']) && isset($_GET['size']) && isset($_GET['quantity'])) {
        // Direct purchase
        $product_id = mysqli_real_escape_string($dbc, $_GET['id']);
        $size = mysqli_real_escape_string($dbc, $_GET['size']);
        $quantity = (int)$_GET['quantity'];

        $q = "SELECT name, price FROM products WHERE id = '$product_id'";
        $r = mysqli_query($dbc, $q);

        if ($r && mysqli_num_rows($r) > 0) {
            $product = mysqli_fetch_assoc($r);
            $items[] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'size' => $size
            ];
            $total += $product['price'] * $quantity;
        } else {
            die('Error fetching product details: ' . mysqli_error($dbc));
        }
    } else {
        // Checkout from cart
        $q = "SELECT p.id, p.name, p.price, c.quantity, c.size 
            FROM carts c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = '$customer_id'";
        $r = mysqli_query($dbc, $q);

        if (!$r) {
            die('Error fetching cart details: ' . mysqli_error($dbc));
        }

        while ($item = mysqli_fetch_assoc($r)) {
            $items[] = $item;
            $total += $item['price'] * $item['quantity'];
        }

        // Check if the cart is empty
        if (empty($items)) {
            echo '<script>alert("Your cart is empty! Please add items to your cart or proceed with an item before checking out."); window.location.href=\'cart.php\';</script>';
            exit();
        }
    }

    // Fetch customer address
    $q = "SELECT address FROM customers WHERE id = '$customer_id'";
    $r = mysqli_query($dbc, $q);

    if ($r && mysqli_num_rows($r) > 0) {
        $customer = mysqli_fetch_assoc($r);
        $address = $customer['address'];
    }

    include("../incl/header.html");
?>
    <body style="margin: 0;">
        <div class="bg-aest" style="background-image: url('../resources/bg1.jpg');">
            <div class="payment">
                <div>
                    <a href="javascript:window.history.back();">
                        <i class="fa fa-angle-left"></i>
                        Cancel Checkout
                    </a>
                    <h1>CHECKOUT</h1>
                </div>
                </br>
                <h2>Order Summary</h2>
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
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['size']); ?></td>
                                <td>RM <?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>RM <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>   
                <table class="tb-total">
                    <colgroup>
                        <col style="width:50%"/>
                        <col style="width:50%"/>
                    </colgroup>
                    <tr>
                        <td></td>
                        <td>
                            <table>
                                <tr>
                                    <td style="font-size: 1.1rem; text-align: right;">Subtotal:</td>
                                    <td style="font-size: 1.1rem; text-align: right; padding-left: 10px;">  RM <span id="subtotal"><?php echo number_format($total, 2); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.1rem; text-align: right;">Delivery Cost:</td>
                                    <td style="font-size: 1.1rem; text-align: right; padding-left: 10px;">  RM <span id="delivery-cost">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 1.1rem; text-align: right;">
                                        <strong>Total:</strong>
                                    </td>
                                    <td style="font-size: 1.1rem; text-align: right; padding-left: 10px;">  <strong>RM <span id="total"><?php echo number_format($total, 2); ?></span></strong>
                                    </td>
                                </tr>
                            </table>                
                        </td>
                    </tr>
                </table>
                <h2>Delivery Address</h2>
                <form action="handler/cus_pay_process.php" method="post">
                    <div class="address-section">
                        <textarea name="address" id="address" rows="4" placeholder="Enter your address" required><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                    </br>
                    <h2>Delivery Option</h2>
                    <div class="delivery-method">
                        <input type="radio" name="delivery_method" id="self-pickup" value="Self-Pickup" data-cost="0.00" checked>
                        <label for="self-pickup">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                            <h4>Self-Pickup</h4>
                            <p>(Business Hours)</p>
                            <p>RM 0.00</p>
                        </label>

                        <input type="radio" name="delivery_method" id="regular_postage" value="Regular Postage" data-cost="2.40">
                        <label for="regular_postage">
                            <i class="fa-solid fa-truck"></i>
                            <h4>Regular Postage</h4>
                            <p>(5-7 days)</p>
                            <p>RM 2.40</p>
                        </label> 

                        <input type="radio" name="delivery_method" id="express_postage" value="Express Postage" data-cost="4.70">
                        <label for="express_postage">
                            <i class="fa-solid fa-truck-fast"></i>
                            <h4>Express Postage</h4>
                            <p>(1-3 days)</p>
                            <p>RM 4.70</p>
                        </label> 
                    </div>
                    </br>
                    <h2>Payment Method</h2>
                    <div class="payment-method">
                        <input type="radio" name="payment_method" id="credit_card" value="Credit Card" checked>
                        <label for="credit_card"><i class="fab fa-cc-mastercard"></i> Credit Card</label>

                        <input type="radio" name="payment_method" id="paypal" value="PayPal">
                        <label for="paypal"><i class="fab fa-cc-paypal"></i> PayPal</label>

                        <input type="radio" name="payment_method" id="bank_transfer" value="Bank Transfer">
                        <label for="bank_transfer"><i class="fas fa-university"></i> Bank Transfer</label>
                    </div>
                    <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total; ?>">
                    <input type="hidden" name="items" value='<?php echo json_encode($items); ?>'>
                    <input type="hidden" name="delivery_cost" id="delivery_cost" value="0.00">
                    <button type="submit" id="checkout-btn" class="grad-fx-plus" onclick="return confirm('Are you sure you want to confirm and pay?');">PLACE ORDER</button>
                </form>
            </div>
        </div>
        <script>
            document.querySelectorAll('input[name="delivery_method"]').forEach(function(el) {
                el.addEventListener('change', function() {
                    const deliveryCost = parseFloat(this.getAttribute('data-cost'));
                    const deliveryMethod = this.value;
                    const subtotal = parseFloat(document.getElementById('subtotal').innerText.replace(',', ''));
                    const total = subtotal + deliveryCost;

                    document.getElementById('delivery-cost').innerText = deliveryCost.toFixed(2);
                    document.getElementById('total').innerText = total.toFixed(2);
                    document.getElementById('total_amount').value = total.toFixed(2);
                    document.getElementById('delivery_cost').value = deliveryCost.toFixed(2);
                });
            });
        </script>
    </body>
</html>
<?php mysqli_close($dbc); ?>