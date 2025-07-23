<?php
    $page_title = 'Admin | Echanem';
    include ('../incl/web_title.html');

    session_start();

    $adm_name = $_SESSION['admin_name'];
    if (!isset($_SESSION['admin_id'])) {
        header('Location: adm_login.php');
        exit();
    }

    include("handler/adm_db_tracking.php");
?>

    <body>
        <div class="cont">
            <?php include 'adm_navbar.php'; ?>
            <main>
                <div class="main-head">
                    <span class="material-symbols-outlined">dashboard</span>
                    <p>Quick overview of key metrics and important information about the store</p>
                    <h5>Dashboard</h5>
                </div>
                <div class="main-content">
                    <div class="dashboard-grid">
                        <!-- Total Sales -->
                        <div class="dashboard-item total-sales">
                            <h3>📈 Total Sales</h3>
                            <p>Total Sales: RM<?php echo number_format(array_sum(array_values($formatted_sales_data)), 2); ?></p>
                            <canvas id="salesChart"></canvas>
                        </div>
                        
                        <!-- Top Selling Products -->
                        <div class="dashboard-item">
                            <h3>🔥 Top Selling Products</h3>
                            <?php if ($top_selling_count > 0) { ?>
                                <ul>
                                    <?php while ($row = mysqli_fetch_assoc($top_selling_result)) { ?>
                                        <li><?php echo htmlspecialchars($row['name']) . ' - ' . $row['total_quantity'] . ' sold'; ?></li>
                                    <?php } ?>
                                </ul>
                            <?php } else { ?>
                                <p>No top selling products available.</p>
                            <?php } ?>
                        </div>

                        <!-- Pending Orders -->
                        <div class="dashboard-item">
                            <div>
                                <h3>🛎️ Pending Orders</h3>
                                <?php if ($pending_orders_count > 0) { ?>
                                <ul>
                                    <?php while ($row1 = mysqli_fetch_assoc($pending_orders_result)) { ?>
                                        <li>Order ID: <?php echo htmlspecialchars($row1['id']); ?> - RM<?php echo htmlspecialchars($row1['total']); ?></li>
                                    <?php } ?>
                                </ul>
                                <?php } else { ?>
                                    <p>No pending orders available.</p>
                                <?php } ?>
                            </div>
                            <div>
                                <h3>✅ Completed Orders</h3>
                                <?php if ($completed_orders_count > 0) { ?>
                                <ul>
                                    <?php while ($row2 = mysqli_fetch_assoc($completed_orders_result)) { ?>
                                        <li>Order ID: <?php echo htmlspecialchars($row2['id']); ?> - RM<?php echo htmlspecialchars($row2['total']); ?></li>
                                    <?php } ?>
                                </ul>
                                <?php } else { ?>
                                    <p>No completed orders available.</p>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <!-- Stock Tracking -->
                        <div class="dashboard-item">
                            <h3>🆗 Normal Stock Levels</h3>
                            <ul>
                                <?php while ($row = mysqli_fetch_assoc($normal_stock_result)) { ?>
                                    <li><?php echo htmlspecialchars($row['name']) . ' - <b>' . $row['stock'] . '</b> in stock'; ?></li>
                                <?php } ?>
                            </ul>

                            <div class="dashboard-item low-stock">
                                <h3 style="font-size: 1.7rem;">⚠️ Low Stock Levels</h3>
                                <ul>
                                    <?php while ($row = mysqli_fetch_assoc($low_stock_result)) { ?>
                                        <li><?php echo htmlspecialchars($row['name']) . ' - <b>' . $row['stock'] . '</b> in stock'; ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>     
            </main>
        </div>
    </body>
    <script>
        // We can refer https://www.w3schools.com/js/js_graphics_chartjs.asp
        var salesDates = <?php echo $sales_dates; ?>;
        var salesTotals = <?php echo $sales_totals; ?>;
        var maxY = Math.ceil(Math.max(...salesTotals) / 10) * 10; // Round up to nearest 10

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesDates,
                datasets: [{
                    label: 'Total Sales',
                    data: salesTotals,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: maxY
                    }
                }
            }
        });
    </script>
</html>
