<?php
$page_title = 'Discover Our Premium Fashions! | Echanem';
include ('../incl/web_title_alt.html');

session_start();
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


// Define the query to retrieve data from `products` table
$q = "SELECT * FROM `products` ORDER BY `created_at` ASC";
$r = mysqli_query($dbc, $q);

include("../incl/header.html");
?>

<body style="margin-top: 12.5vh;">
    <div class="title">
        <p>Echanem&#8482 <span>BASIC</span></p>
        <hr>
    </div>
    <div class="container">
        <div class="sidebar">
            <form method="GET" action="catalogue.php">
                <input type="text" name="search" placeholder="&#128269; Search...">
                <button type="button" class="collapsible">FOR</button>
                <div class="content">
                    <label>
                        <input type="checkbox" name="gender[]" value="Men"> Men
                    </label>
                    <label>
                        <input type="checkbox" name="gender[]" value="Women"> Women
                    </label>
                    <label>
                        <input type="checkbox" name="gender[]" value="Unisex"> Unisex
                    </label>
                </div>
                <button type="button" class="collapsible">CATEGORY</button>
                <div class="content">
                    <label>
                        <input type="checkbox" name="category[]" value="Tops & Tees"> Tops & Tees
                    </label>
                    <label>
                        <input type="checkbox" name="category[]" value="Bottoms"> Bottoms
                    </label>
                    <label>
                        <input type="checkbox" name="category[]" value="Footwear"> Footwear
                    </label>
                </div>
                <button type="button" class="collapsible">SIZE</button>
                <div class="content">
                    <label>
                        <input type="checkbox" name="size[]" value="S"> S
                    </label>
                    <label>
                        <input type="checkbox" name="size[]" value="M"> M
                    </label>
                    <label>
                        <input type="checkbox" name="size[]" value="L"> L
                    </label>
                    <label>
                        <input type="checkbox" name="size[]" value="XL"> XL
                    </label>
                    <label>
                        <input type="checkbox" name="size[]" value="XXL"> XXL
                    </label>
                </div>
                <button type="submit" id="apply-filter" class="grad-fx-plus">Apply Filters</button>
            </form>
        </div>

        <div class="catalog">
            <?php
            // Build the query with filters
            $query = "SELECT * FROM products WHERE 1";
            
            // Search filter
            if (!empty($_GET['search'])) {
                $search = mysqli_real_escape_string($dbc, $_GET['search']);
                $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
            }
            
            // Gender filter
            if (!empty($_GET['gender'])) {
                $gender_filter = implode("','", $_GET['gender']);
                $query .= " AND gendertype IN ('$gender_filter')";
            }
            
            // Category filter
            if (!empty($_GET['category'])) {
                $category_filter = implode("','", $_GET['category']);
                $query .= " AND category IN ('$category_filter')";
            }
            
            // Size filter
            if (!empty($_GET['size'])) {
                $size_filter = $_GET['size'];
                foreach ($size_filter as &$size) {
                    $size = mysqli_real_escape_string($dbc, $size);
                }
                $size_filter = implode("%' OR sizes LIKE '%", $size_filter);
                $query .= " AND (sizes LIKE '%$size_filter%')";
            }

            // Execute the query
            $r = mysqli_query($dbc, $query);
            
            if (!$r) {
                echo '<p>Error executing query: ' . mysqli_error($dbc) . '</p>';
            } else {
                if (mysqli_num_rows($r) > 0) {
                    while ($res = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                        $stok_low = $res['stock'];
                        // Don't display if item stock is 0
                        if($stok_low > 0) {
                            ?>
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($res['image_url']); ?>" alt="<?php echo htmlspecialchars($res['name']); ?>">
                                <div class="card-info">
                                    <p class="card-text"><?php echo htmlspecialchars($res['name']); ?></p>
                                    <div class="categorization">
                                        <?php 
                                        $sizes_arr = json_decode($res['sizes'], true);
                                        $size_txt = implode(", ", $sizes_arr);
                                        ?>
                                        <p><?php echo htmlspecialchars($size_txt); ?></p>
                                    </div>
                                    <div class="gnp">
                                        <span><?php echo htmlspecialchars($res['gendertype']); ?></span>
                                        <p>RM <?php echo htmlspecialchars($res['price']); ?></p>
                                    </div>
                                </div>

                                <?php
                                if(!isset($_SESSION['user_id'])) { ?>
                                    <div class="card-buttons">
                                        <button onclick="gotoProdDetail(<?php echo $res['id']; ?>)">Buy Now</button>
                                    </div>
                                <?php 
                                } else { ?>
                                    <div class="card-buttons">
                                        <button onclick="addToCartCatalogue(<?php echo $res['id']; ?>)">Add to Cart</button>
                                        <button onclick="gotoProdDetail(<?php echo $res['id']; ?>)">Buy Now</button>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($res['image_url']); ?>" alt="<?php echo htmlspecialchars($res['name']); ?>">
                                <div class="card-info">
                                    <p class="card-text"><?php echo htmlspecialchars($res['name']); ?></p>
                                    <div class="categorization">
                                        <?php 
                                        $sizes_arr = json_decode($res['sizes'], true);
                                        $size_txt = implode(", ", $sizes_arr);
                                        ?>
                                        <p><?php echo htmlspecialchars($size_txt); ?></p>
                                    </div>
                                    <div class="gnp">
                                        <span><?php echo htmlspecialchars($res['gendertype']); ?></span>
                                        <p>RM <?php echo htmlspecialchars($res['price']); ?></p>
                                    </div>
                                </div>
                                <div class="card-buttons">
                                    <p><strong>OUT OF STOCK</strong></p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo '<p>No products available with the selected filters.</p>';
                }
            }
            
            mysqli_close($dbc);
            ?>
        </div>
    </div>
</body>
<script>
    // Script for collapse/expand filters
    document.querySelectorAll('.collapsible').forEach(button => {
        button.addEventListener('click', () => {
            button.classList.toggle('active');
            const content = button.nextElementSibling;
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        });
    });
</script>
<script src="../incl/skrip.js"></script>
</html>
