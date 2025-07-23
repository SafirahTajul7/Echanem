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
        $name = mysqli_real_escape_string($dbc, $_POST['name']);
        $desc = mysqli_real_escape_string($dbc, $_POST['description']);
        $price = mysqli_real_escape_string($dbc, $_POST['price']);
        $stock = mysqli_real_escape_string($dbc, $_POST['stock']);
        $category = mysqli_real_escape_string($dbc, $_POST['category']);
        $gtype = mysqli_real_escape_string($dbc, $_POST['gtype']);
        $img_src = mysqli_real_escape_string($dbc, $_POST['image_url']);
        $q = "UPDATE `products` SET name = '$name', description = '$desc', price = '$price', stock = '$stock', category = '$category', gendertype = '$gtype', image_url = '$img_src' WHERE id = '$edit_id'";
        $r = mysqli_query($dbc, $q);
        if ($r) {
            echo '<script>alert("Product info updated successfully.");</script>';
        } else {
            echo '<script>alert("Error updating product info: ' . mysqli_error($dbc) . '");</script>';
        }
    }

    // Define the query to retrieve data from `customers` table
    $q = "SELECT `id`, `name`, `description`, `price`, `stock`, `sizes`, `category`, `gendertype`, `image_url`, DATE_FORMAT(`created_at`, '%M %d, %Y') AS `date_created` FROM `products` ORDER BY `created_at` ASC";
    $r = mysqli_query($dbc, $q);

    // Count the number of returned rows
    $prod_num = mysqli_num_rows($r);
?>

    <body>
        <div class="cont">
            <?php
                // Include sidebar
                include 'adm_navbar.php';
            ?>
            <main>
                <div class="main-head">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <p>Add, edit, and manage all the clothing items in the store</p>
                    <h5>Manage Products</h5>
                </div>
                <div class="main-content">
                    <div class="content-cont">
                        <div id="prod-list">
                            <div class="s-flex">
                                <h3>📦 Products List</h3>
                                <button id="openModal-btn" class="create-btn grad-fx-plus">
                                    <i class="fa-solid fa-circle-plus"></i> 
                                    Add New Product
                                </button>
                            </div>
                            <?php
                            if ($prod_num > 0) { // If it ran OK, display the records
                                echo "<p>There are currently $prod_num products.</p>";
                                echo '<div class="table-rx"><table class="table-dsgn">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price (MYR)</th>
                                        <th>Stock</th>
                                        <th>Sizes</th>
                                        <th>Category</th>
                                        <th>Gender Type</th>
                                        <th>Image</th>
                                        <th>Edit Info</th>
                                        <th>Delete Product</th>
                                    </tr>
                                </thead>
                                <tbody>';

                                while ($res = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                    echo '<tr>
                                        <td>' . htmlspecialchars($res['name']) . '</td>
                                        <td>' . htmlspecialchars($res['description']) . '</td>
                                        <td>' . htmlspecialchars($res['price']) . '</td>
                                        <td>' . htmlspecialchars($res['stock']) . '</td>
                                        <td>' . htmlspecialchars(json_encode(json_decode($res['sizes']))) . '</td>
                                        <td>' . htmlspecialchars($res['category']) . '</td>
                                        <td>' . htmlspecialchars($res['gendertype']) . '</td>
                                        <td><img src="' . htmlspecialchars($res['image_url']) . '" alt="' . htmlspecialchars($res['name']) . '" height="50"></td>
                                        <td><a href="javascript:void(0);" class="edit-btn" onclick="editProduct(
                                            \'' . $res['id'] . '\', 
                                            \'' . htmlspecialchars($res['name'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['description'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['price'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['stock'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars(json_encode(json_decode($res['sizes'])), ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['category'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars($res['gendertype'], ENT_QUOTES) . '\', 
                                            \'' . htmlspecialchars(addslashes($res['image_url']), ENT_QUOTES) . '\'
                                        )">EDIT</a></td>
                                        <td><form action="" method="post" style="display:inline;" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">
                                            <input type="hidden" name="del_id" value="' . $res['id'] . '">
                                            <button type="submit" class="delete-btn">DELETE</button>
                                        </form></td>
                                    </tr>';
                                }

                                echo '</tbody></table></div>';
                                mysqli_free_result($r); // Free memory associated with $r    

                            } else { // If no records were returned
                                echo '<p class="error">There are currently no products.</p>';
                            }

                            if (isset($_POST['del_id'])) {
                                $del_id = $_POST['del_id'];
                                $q = "DELETE FROM `products` WHERE `id` = '$del_id'";
                                $r = mysqli_query($dbc, $q);
                                if ($r) { echo '<p>Product deleted successfully.</p>'; } 
                                else { echo '<p class="error">Error deleting product: ' . mysqli_error($dbc) . '</p>'; }
                            }

                            mysqli_close($dbc); // Close the database connection
                            ?>
                        </div>

                        <div id="prod-update">
                            <h2>🏷️ Edit Product Information</h2>
                            <form action="adm_products.php" method="post">
                                <input type="hidden" id="edit_id" name="edit_id"/>

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" required/>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" rows="4"></textarea>
                                </div>

                                <div class="s-flex" style="gap: 12px;">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" id="price" name="price" min="0.00" step="0.01" required/>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" id="stock" name="stock" min="0" required/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sizes">Sizes (JSON format)</label>
                                    <input type="text" id="sizes" name="sizes" required/>
                                </div>
                                
                                <div class="s-flex" style="gap: 12px;">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select id="category" name="category" required>
                                            <option value="">Select a category</option>
                                            <option value="Tops & Tees">Tops & Tees</option>
                                            <option value="Bottoms">Bottoms</option>
                                            <option value="Footwear">Footwear</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Gender</label>
                                        <select id="gtype" name="gtype" required>
                                            <option value="">Select a gender</option>
                                            <option value="Men">Men</option>
                                            <option value="Women">Women</option>
                                            <option value="Unisex">Unisex</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image_url">Image URL</label>
                                    <input type="text" id="image_url" name="image_url"/>
                                </div>

                                <input type="submit" id="update_button" name="submit" value="UPDATE INFO"/>
                            </form> 
                        </div>
                    </div>
                </div>
                <div id="back-face" class="modal-bg">
                    <div class="modal-cont">
                        <div id="prod-create">
                            <span class="material-symbols-outlined close">close</span>
                            <h2>➕ Add New Product</h2>
                            <form action="handler/adm_prod_add.php" method="post">
                                <input type="hidden" id="edit_id" name="edit_id"/>

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" required/>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" rows="4"></textarea>
                                </div>

                                <div class="s-flex" style="gap: 12px;">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" id="price" name="price" min="0.00" step="0.01" required/>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" id="stock" name="stock" min="0" required/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sizes">Sizes (JSON format)</label>
                                    <input type="text" id="sizes" name="sizes" required/>
                                </div>
                                
                                <div class="s-flex" style="gap: 12px;">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select id="category" name="category" required>
                                            <option value="">Select a category</option>
                                            <option value="Tops & Tees">Tops & Tees</option>
                                            <option value="Bottoms">Bottoms</option>
                                            <option value="Footwear">Footwear</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Gender</label>
                                        <select id="gtype" name="gtype" required>
                                            <option value="">Select a gender</option>
                                            <option value="Men">Men</option>
                                            <option value="Women">Women</option>
                                            <option value="Unisex">Unisex</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image_url">Image URL</label>
                                    <input type="text" id="image_url" name="image_url"/>
                                </div>

                                <input type="submit" id="create_button" class="grad-fx-plus" name="submit" value="ADD PRODUCT"/>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
    <script src="../incl/skrip.js"></script>
    <script>openCloseModal("back-face", "openModal-btn", "close");</script>
</html>
