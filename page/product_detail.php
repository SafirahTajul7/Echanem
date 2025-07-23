<?php
    session_start();
    $page_title = 'Product Detail | Echanem';
    include('../incl/web_title_alt.html');

    $greet = '';
    $profile_button = '';

    if (isset($_SESSION['user_id'])) {
        $cus_name = $_SESSION['user_name'];
        $greet = "Hello, " . $cus_name;
        $profile_button = '<a href="profile.php"><i class="fas fa-user"></i> My Profile</a>';
        $log_button = '<a href="../access/logout.php" onclick="return confirm(\'Are you sure you want to logout?\')"><i class="fas fa-sign-out"></i> Logout</a>';
    } else {
        $log_button = '<a href="../access/login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a>';
    }

    $product_id = isset($_GET['id']) ? mysqli_real_escape_string($dbc, $_GET['id']) : '';

    if (empty($product_id)) {
        die('Product ID is required.');
    }

    $q = "SELECT * FROM products WHERE id = '$product_id'";
    $r = mysqli_query($dbc, $q);

    if (!$r) {
        die('Error fetching product details: ' . mysqli_error($dbc));
    }

    $product = mysqli_fetch_assoc($r);
    if (!$product) {
        die('Product not found.');
    }

    $prod_name = $product['name'];
    include("../incl/header.html");

    // Fetch recommended products (excluding the current product and only if stock is greater than 0)
    // $q_recommended = "SELECT id, name, price, image_url FROM products WHERE id != '$product_id' AND stock > 0 LIMIT 4";
    $q_recommended = "SELECT id, name, price, image_url FROM products WHERE id != '$product_id'";
    $r_recommended = mysqli_query($dbc, $q_recommended);

    $recommended_products = [];
    while ($row = mysqli_fetch_assoc($r_recommended)) {
        $recommended_products[] = $row;
    }
?>

    <body style="margin-top: 10vh;">
        <div class="product-detail">
            <div class="image-container">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="details">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <h3><?php echo htmlspecialchars($product['gendertype']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                
                <?php if ($product['stock'] > 0) { ?>
                    <p><?php echo '<b>Stock: </b>' . htmlspecialchars($product['stock']); ?></p>
                    <div class="price">RM <?php echo htmlspecialchars($product['price']); ?></div>

                    <div class="size-selection">
                        <label for="size">Size:</label>
                        <select id="size-select">
                            <?php
                            $sizes = json_decode($product['sizes']);
                            foreach ($sizes as $size) {
                                echo '<option value="' . htmlspecialchars($size) . '">' . htmlspecialchars($size) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="quantity-selection">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1">
                    </div>
                    <div class="buttons">
                        <button onclick="addToCartDetail(<?php echo $product['id']; ?>, <?php echo $product['stock']; ?>)"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Add to Cart</button>
                        <button onclick="buyNow(<?php echo $product['id']; ?>, <?php echo $product['stock']; ?>)">Buy Now</button>
                    </div>
                <?php
                // If the product item has 0 stock
                } else { ?>
                    <p style="color: #7000ff; font-size: 1.2rem;"><strong>Out of Stock</strong></p>
                    <div class="price">RM <?php echo htmlspecialchars($product['price']); ?></div>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="recommended-products">
            <h2>Recommended Products</h2>
            <hr></br>
            <div class="recommended-grid">
                <?php foreach ($recommended_products as $rec_product): ?>
                    <div class="rec-card" onclick="window.location.href='product_detail.php?id=<?php echo $rec_product['id']; ?>'">
                        <img src="<?php echo htmlspecialchars($rec_product['image_url']); ?>" alt="<?php echo htmlspecialchars($rec_product['name']); ?>">
                        <p class="rec-name"><?php echo htmlspecialchars($rec_product['name']); ?></p>
                        <div class="rec-price">RM <?php echo htmlspecialchars($rec_product['price']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script src="../incl/skrip.js"></script>
    </body>
    </html>
<?php mysqli_close($dbc); ?>
