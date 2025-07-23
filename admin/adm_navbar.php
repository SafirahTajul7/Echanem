<?php
    // Get the current page name to set the active class
    $current_page = basename($_SERVER['PHP_SELF']);
?>
    
    <aside class="navbar grad-fx">
        <div class="logo">
            <img src="../resources/adm-pfp.png" alt="logo">
            <h2><?php echo $adm_name; ?></h2>
        </div>
        <ul class="links">
            <h4>Main Menu</h4>
            <li class="<?php echo ($current_page == 'adm_dashboard.php') ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">dashboard</span>
                <a href="adm_dashboard.php">Dashboard</a>
            </li>
            <li class="<?php echo ($current_page == 'adm_products.php') ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">inventory_2</span>
                <a href="adm_products.php">Products</a>
            </li>
            <li class="<?php echo ($current_page == 'adm_users.php') ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">group</span>
                <a href="adm_users.php">Users</a>
            </li>
            <li class="logout-link">
                <span class="material-symbols-outlined">logout</span>
                <a href="adm_logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
            </li>
        </ul>
        <div id="nav-cp">
            <p><b>Echanem Admin Panel v0.2</b></p>
            <p>&#169 2024 All Rights Reserved</p>
            </br><p>Made ❤️ with by G4</p>
        </div>
    </aside>