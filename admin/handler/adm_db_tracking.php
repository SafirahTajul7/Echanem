<?php
    // Fetch sales data from the database for the specified date range
    $sales_data_query = "SELECT DATE(created_at) as sale_date, SUM(total) as total_sales  
    FROM orders 
    WHERE created_at BETWEEN '2024-06-19' AND '2024-06-25'
    GROUP BY sale_date";
    $sales_data_result = mysqli_query($dbc, $sales_data_query);
    $sales_data = [];
    while ($row = mysqli_fetch_assoc($sales_data_result)) {
        $sales_data[$row['sale_date']] = $row['total_sales'];
    }
    // Fill in missing dates with zero sales
    $start_date = new DateTime('2024-06-19');
    $end_date = new DateTime('2024-06-25');
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($start_date, $interval, $end_date->modify('+1 day'));

    $formatted_sales_data = [];
    foreach ($period as $date) {
        $formatted_sales_data[$date->format('Y-m-d')] = isset($sales_data[$date->format('Y-m-d')]) ? $sales_data[$date->format('Y-m-d')] : 0;
    }
    // Convert PHP array to JSON
    $sales_dates = json_encode(array_keys($formatted_sales_data));
    $sales_totals = json_encode(array_values($formatted_sales_data));


    // Fetch top selling products
    $top_selling_query = "SELECT p.name, SUM(oi.quantity) AS total_quantity FROM order_items oi JOIN products p ON oi.product_id = p.id  GROUP BY p.id ORDER BY total_quantity DESC LIMIT 5";
    $top_selling_result = mysqli_query($dbc, $top_selling_query);
    $top_selling_count = mysqli_num_rows($top_selling_result);


    // Fetch products with normal stock levels
    $normal_stock_query = "SELECT * FROM products WHERE stock >= 10";
    $normal_stock_result = mysqli_query($dbc, $normal_stock_query);
    // Fetch products with low stock levels
    $low_stock_query = "SELECT * FROM products WHERE stock < 10";
    $low_stock_result = mysqli_query($dbc, $low_stock_query);


    // Fetch pending orders
    $pending_orders_query = "SELECT * FROM orders WHERE status = 'Pending'";
    $pending_orders_result = mysqli_query($dbc, $pending_orders_query);
    $pending_orders_count = mysqli_num_rows($pending_orders_result);

    // Fetch completed orders
    $completed_orders_query = "SELECT * FROM orders WHERE status = 'Completed'";
    $completed_orders_result = mysqli_query($dbc, $completed_orders_query);
    $completed_orders_count = mysqli_num_rows($completed_orders_result);
?>
