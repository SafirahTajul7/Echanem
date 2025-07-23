<?php
    $page_title = 'Admin | Echanem';
    include ('../../incl/web_title.html');
    session_start();

    // if (!isset($_SESSION['admin_id'])) {
    //     header('Location: ../adm_login.php');
    //     exit();
    // }

    // Handle edit form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = mysqli_real_escape_string($dbc, $_POST['name']);
        $desc = mysqli_real_escape_string($dbc, $_POST['description']);
        $price = mysqli_real_escape_string($dbc, $_POST['price']);
        $stock = mysqli_real_escape_string($dbc, $_POST['stock']);
        $size = mysqli_real_escape_string($dbc, $_POST['sizes']);
        $category = mysqli_real_escape_string($dbc, $_POST['category']);
        $img_src = mysqli_real_escape_string($dbc, $_POST['image_url']);

        $q = "INSERT INTO `products` (`name`, `description`, `price`, `stock`, `sizes`, `category`, `image_url`) VALUES ('$name', '$desc', '$price', '$stock', '$size', '$category', '$img_src')";
        $r = mysqli_query($dbc, $q);
        if ($r) {
            echo '<script>
                alert("Product added successfully.");
                window.location.href=\'../adm_products.php\';
            </script>';
        } else {
            echo '<script>alert("Error adding product info: ' . mysqli_error($dbc) . '"); window.location.href=\'../adm_products.php\';</script>';
        }
    }
?>