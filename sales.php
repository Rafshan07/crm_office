<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {
    header("Location: index.php");
    exit();
}

$db = new database();


$total_sales_query = "SELECT SUM(totalamount) as total_sales FROM `order` WHERE status = 'completed'";
$total_sales_result = $db->select($total_sales_query);
$total_sales = $total_sales_result ? $total_sales_result->fetch(PDO::FETCH_ASSOC)['total_sales'] : 0;

$total_leads_query = "SELECT COUNT(*) as total_leads FROM lead";
$total_leads_result = $db->select($total_leads_query);
$total_leads = $total_leads_result ? $total_leads_result->fetch(PDO::FETCH_ASSOC)['total_leads'] : 0;

$total_opportunities_query = "SELECT COUNT(*) as total_opportunities FROM opportunity";
$total_opportunities_result = $db->select($total_opportunities_query);
$total_opportunities = $total_opportunities_result ? $total_opportunities_result->fetch(PDO::FETCH_ASSOC)['total_opportunities'] : 0;


$total_tasks_query = "SELECT COUNT(*) as total_tasks FROM task";
$total_tasks_result = $db->select($total_tasks_query);
$total_tasks = $total_tasks_result ? $total_tasks_result->fetch(PDO::FETCH_ASSOC)['total_tasks'] : 0;

$order_status_query = "SELECT 
                            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled
                        FROM `order`";
$order_status_result = $db->select($order_status_query);
$order_status = $order_status_result ? $order_status_result->fetch(PDO::FETCH_ASSOC) : ['pending' => 0, 'completed' => 0, 'cancelled' => 0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Sales Dashboard</title>
    <style>
        /* Add margin to the left side of the dashboard */
        .dashboard-content {
            margin-left: 250px;
            /* Leaving 250px from the left side */
        }
    </style>
</head>

<body class="adminpage">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4>..Sales</h4>
                </div>
                <!-- Menu Items -->
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./sales.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-solid fa-pencil"></i>
                        <span class="ms-2">Leads</span>
                        <div class="submenu">
                            <a href="./add_leads.php" class="no-underline">
                                <div>Add Leads</div>
                            </a>
                            <a href="./updatelead.php" class="no-underline">
                                <div>Update leads</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <i class="fa-brands fa-android"></i>
                        <span class="ms-2">Product</span>
                        <div class="submenu">
                            <a href="./add_product.php" class="no-underline">
                                <div>add Product</div>
                            </a>
                            <a href="./order_details.php" class="no-underline">
                                <div>order details</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./opprtunity.php" class="nav-link d-flex align-items-center">
                            <i class="fa-brands fa-codepen"></i>
                            <span class="ms-2">Opportunities</span>
                        </a>
                    </div>
                    <div class="nav-button">
                        <a href="./sales_tasks.php" class="nav-link d-flex align-items-center">
                            <i class="fa-solid fa-list-check"></i>
                            <span class="ms-2">tasks</span>
                        </a>
                    </div>
                    <a href="./customer_data.php" class="nav-button text-decoration-none">
                        <i class="fa-solid fa-database"></i>
                        <span class="ms-2">customer data</span>
                    </a>
                </div>

                <!-- Footer Section -->
                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6>Sales</h6>
                    <p>Sales</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <!-- Main Dashboard Content -->
            <div class="col-md-9 col-lg-10 p-4" id="dashboard" style="flex: 1; margin-left: 250px;">
                <h2 class="dashboard-title text-start">Sales Dashboard</h2>

                <!-- Row for Total Sales Count and Order Status (Side-by-Side) -->
                <div class="row mb-4">
                    <!-- Total Sales Card -->
                    <div class="col-md-6">
                        <div class="dashboard-card shadow-lg rounded p-3 bg-light">
                            <h4>Total Sales</h4>
                            <div class="total-sales">
                                <h3>$<?= number_format($total_sales, 2); ?></h3> <!-- Example total sales amount -->
                            </div>
                        </div>
                    </div>

                    <!-- Order Status Card -->
                    <div class="col-md-6">
                        <div class="dashboard-card shadow-lg rounded p-3 bg-light">
                            <h4>Order Status</h4>
                            <div class="order-status">
                                <p>Pending: <?= $order_status['pending']; ?></p>
                                <p>Completed: <?= $order_status['completed']; ?></p>
                                <p>Cancelled: <?= $order_status['cancelled']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row for Charts - 3 Charts Side by Side -->
                <div class="row mb-4">
                    <!-- Leads Chart -->
                    <div class="col-md-4">
                        <div class="dashboard-card shadow-lg rounded p-3 bg-light">
                            <h4>Leads Overview</h4>
                            <canvas id="leadsChart" width="250" height="150"></canvas>
                        </div>
                    </div>

                    <!-- Opportunities Chart -->
                    <div class="col-md-4">
                        <div class="dashboard-card shadow-lg rounded p-3 bg-light">
                            <h4>Opportunities Overview</h4>
                            <canvas id="opportunitiesChart" width="250" height="150"></canvas>
                        </div>
                    </div>

                    <!-- Tasks Chart -->
                    <div class="col-md-4">
                        <div class="dashboard-card shadow-lg rounded p-3 bg-light">
                            <h4>Tasks Overview</h4>
                            <canvas id="tasksChart" width="250" height="150"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Additional Styles -->
        <style>
            /* General dashboard styles */
            #dashboard {
                margin-left: 250px;
                /* Adjust to leave space for sidebar */
            }

            .dashboard-title {
                font-size: 2rem;
                font-weight: 600;
                margin-bottom: 1.5rem;
            }

            .dashboard-card {
                background-color: #f8f9fa;
                /* Light background for cards */
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .total-sales h3 {
                color: #28a745;
                /* Green for total sales */
                font-weight: bold;
            }

            .order-status p {
                font-size: 1rem;
                color: #007bff;
                /* Blue for order status */
            }

            .bg-light {
                background-color: #f8f9fa !important;
                /* Ensures light background */
            }

            canvas {
                width: 100% !important;
                height: auto !important;
            }

            /* Padding for cards */
            .dashboard-card .p-3 {
                padding: 20px;
            }

            /* Row gap between cards */
            .row.mb-4 {
                margin-bottom: 1.5rem;
            }
        </style>

    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example chart for Total Sales
        const salesData = {
            labels: ['Total Sales'],
            datasets: [{
                label: 'Sales Value',
                data: [<?= $total_sales; ?>],
                backgroundColor: ['#ffcc00'],
                borderColor: ['#e0b300'],
                borderWidth: 1
            }]
        };

        const salesConfig = {
            type: 'doughnut',
            data: salesData,
        };

        const salesChart = new Chart(document.getElementById('salesChart'), salesConfig);

        // Example chart for Total Leads
        const leadsData = {
            labels: ['Leads Overview'], // We are using one label for simplicity
            datasets: [{
                label: 'Total Leads',
                data: [<?= $total_leads; ?>], // Insert PHP variable for total leads
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true, // Makes the line chart filled
            }]
        };

        const leadsConfig = {
            type: 'line', // Change type to 'line'
            data: leadsData,
        };

        const leadsChart = new Chart(document.getElementById('leadsChart'), leadsConfig);


        // Example chart for Total Opportunities
        const opportunitiesData = {
            labels: ['Opportunities Overview'], // One label for now, can be expanded for more data
            datasets: [{
                label: 'Total Opportunities',
                data: [<?= $total_opportunities; ?>], // Insert PHP variable for opportunities
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
            }]
        };

        const opportunitiesConfig = {
            type: 'line', // Change type to 'line'
            data: opportunitiesData,
        };

        const opportunitiesChart = new Chart(document.getElementById('opportunitiesChart'), opportunitiesConfig);



        // Example chart for Total Tasks
        const tasksData = {
            labels: ['Tasks Overview'], // One label for now
            datasets: [{
                label: 'Total Tasks',
                data: [<?= $total_tasks; ?>], // Insert PHP variable for tasks
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true,
            }]
        };

        const tasksConfig = {
            type: 'line', // Change type to 'line'
            data: tasksData,
        };

        const tasksChart = new Chart(document.getElementById('tasksChart'), tasksConfig);
    </script>
    <script src="./assets/js/addtasks.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

</body>

</html>