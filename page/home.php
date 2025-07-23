<?php
    $page_title = 'Home | Echanem';
    include ('../incl/web_title.html');
    
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
?>
<?php include("../incl/header.html"); ?>
<body>
    <div class="slideshow-container">
        <div class="main-banner mySlides fade" style="background-image: url('images/summer.jpg');">
            <h1>NEW ARRIVALS</h1>
            <h2>Fashion For Every Season</h2>
            <a href="catalogue.php"><button >Shop Now</button></a>
        </div>
        <div class="main-banner mySlides fade" style="background-image: url('images/winter.jpg');">
            <h1>WINTER COLLECTION</h1>
            <h2>WARM & COZY</h2>
            <a href="catalogue.php"><button >Shop Now</button></a>
        </div>
        <div class="main-banner mySlides fade" style="background-image: url('images/spring.jpg');">
            <h1>SPRING TRENDS</h1>
            <h2>FRESH LOOKS</h2>
            <a href="catalogue.php"><button >Shop Now</button></a>
        </div>
        <a class="prev" onclick="prevSlide()">❮</a>
        <a class="next" onclick="nextSlide()">❯</a>
    </div>
    <div class="category-links">
        <button class="active" onclick="filterCategory('all')">All</button>
        <button onclick="filterCategory('women')">Women</button>
        <button onclick="filterCategory('men')">Men</button>
    </div>

<div class="product-list">
    <div class="product" data-category="women">
        <img src="images/download.jpeg" alt="Skirt">
        <p>Tops & Tees</p>
		<p>Oversized Graphic Tee</p>
    </div>
    <div class="product" data-category="women">
        <img src="images/levis.jpeg" alt="Blouse">
        <p>Bottoms</p>
		<p>Cargo Pants</p>
    </div>
    <div class="product" data-category="women">
        <img src="images/OIP.jpeg" alt="Maxi Dress">
        <p>Footwear</p>
		<p>White Sneakers</p>
    </div>
    <div class="product" data-category="women">
        <img src="images/images.jpeg" alt="Maxi Skirt">
        <p>Tops & Tees</p>
		<p>Windbreaker Jacket</p>
    </div>
    <div class="product" data-category="men">
        <img src="images/download.jpeg" alt="Skirt">
        <p>Bottoms</p>
		<p>Techwear Leggings</p>
		
    </div>
    <div class="product" data-category="men">
        <img src="images/levis.jpeg" alt="Blouse">
        <p>Tops & Tees</p>
		<p>Sleeveless Hoodie</p>
    </div>
    <div class="product" data-category="men">
        <img src="images/levis.jpeg" alt="Blouse">
        <p>Tops & Tees</p>
		<p>Sleeveless Hoodie</p>
    </div>
	<div class="product" data-category="men">
        <img src="images/levis.jpeg" alt="Blouse">
        <p>Tops & Tees</p>
		<p>Sleeveless Hoodie</p>
    </div>
</div>

    <script src="../incl/slideshow.js"></script>
	
	<!-- Footer -->
    <footer style="background-image: url('images/header.jpeg');">
    <div class="social-icons">
	<p>follow us on</p>
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
    </div>
    <p>Echanem brand clothing store</p>
</body>
</html>
