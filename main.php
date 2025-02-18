<?php
session_start();
require_once 'lib/database.php';
require_once 'lib/user.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Create a database object
$db = new database();

// Fetch statistics data from the database
$totalUsersQuery = "SELECT COUNT(*) FROM user";
$totalProductsQuery = "SELECT COUNT(*) FROM product";
$totalInvoicesQuery = "SELECT COUNT(*) FROM invoices";

// Execute queries to fetch data
$totalUsersStmt = $db->select($totalUsersQuery);
$totalProductsStmt = $db->select($totalProductsQuery);
$totalInvoicesStmt = $db->select($totalInvoicesQuery);

// Get the values
$totalUsers = $totalUsersStmt ? $totalUsersStmt->fetchColumn() : 0;
$totalProducts = $totalProductsStmt ? $totalProductsStmt->fetchColumn() : 0;
$totalInvoices = $totalInvoicesStmt ? $totalInvoicesStmt->fetchColumn() : 0;


if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid']; // Get the user ID from the session

    // Instantiate the User class and fetch the user data
    $user = new User();
    $userData = $user->getUserById($userId); // Fetch user data by UserID

    if ($userData) {
        $loggedInUserName = $userData['name']; // Get the logged-in user's name
    } else {
        $loggedInUserName = 'Guest'; // Fallback if no user is found
    }
} else {
    $loggedInUserName = 'Guest'; // Default name if no user is logged in
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>apcorn crm</title>
    <style>
        /* Chat icon styling */
        .chat-container {
            position: fixed;
            top: 20px;
            right: 30px;
            z-index: 1000;
        }

        .chat-icon {
            font-size: 1.8rem;
            color: #fff;
            cursor: pointer;
            background: var(--primary-gradient);
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .chat-dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 200px;
            padding: 10px 0;
        }

        .chat-dropdown a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            transition: background 0.3s ease;
        }

        .chat-dropdown a:hover {
            background: #f1f1f1;
        }

        /* Admin Dashboard Styling */
        .dashboard {
            padding: 30px;
            margin-left: 250px;
            /* Leave space for sidebar */
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dashboard h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .dashboard p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            color: #666;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            margin: 0;
            font-size: 1.8rem;
            color: #333;
        }

        .card p {
            font-size: 1.4rem;
            color: #777;
            margin-top: 5px;
        }

        .card-icon {
            font-size: 4rem;
            color: var(--primary-gradient);
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .col {
            flex: 1 1 30%;
        }

        .col-12 {
            flex: 1 1 100%;
        }

        .chart-container {
            height: 400px;
            margin-top: 50px;
        }

        /* Style the footer section */
        #nav-footer {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
        }

        #nav-footer h6 {
            font-size: 1.2rem;
            color: white;
            margin-bottom: 5px;
        }

        #nav-footer p {
            color: #888;
            font-size: 1rem;
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
                    <h4>Admin Panel</h4>
                </div>
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./main.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                        <div class="submenu">
                            <a href="./_manage_users_create.php" class="no-underline">
                                <div>Create</div>
                            </a>
                            <a href="./upgradefrom.php" class="no-underline">
                                <div>All Users</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-chart-bar"></i>
                        <span>View Reports</span>
                        <div class="submenu">
                            <a href="./all_repot.php" class="no-underline">
                                <div>All Reports</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                        <div class="submenu">
                            <a href="./_products_new.php" class="no-underline">
                                <div>Our Product</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <i class="fa-solid fa-file-invoice"></i>
                        <span>Invoice</span>
                        <div class="submenu">
                            <a href="./invoice.php" class="no-underline">
                                <div>Generatte Invoice</div>
                            </a>
                            <a href="./all_invoice.php" class="no-underline">
                                <div>All Invoice</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6><?php echo htmlspecialchars($loggedInUserName); ?></h6>
                    <p>Admin</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <!-- Main Dashboard Content -->
            <div class="col-md-9 col-lg-10 p-4 right">
                <div class="dashboard_admin">
                    <h2>Welcome, Admin</h2>
                    <p>Here is your dashboard overview.</p>

                    <!-- Dashboard Cards -->
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div>
                                    <h3>Users</h3>
                                    <p>Total Users: <?php echo $totalUsers; ?></p>
                                </div>
                                <i class="fas fa-users card-icon"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div>
                                    <h3>Products</h3>
                                    <p>Total Products: <?php echo $totalProducts; ?></p>
                                </div>
                                <i class="fas fa-box card-icon"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div>
                                    <h3>Invoices</h3>
                                    <p>Total Invoices: <?php echo $totalInvoices; ?></p>
                                </div>
                                <i class="fas fa-file-invoice card-icon"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Icon -->
    <div class="chat-container">
        <i class="fas fa-comments chat-icon" onclick="toggleChat()"></i>
        <div class="chat-dropdown" id="chatDropdown">
            <a href="#">📧 Mail</a>
            <a href="#">💬 Messages</a>
        </div>
    </div>

    <script>
        function toggleChat() {
            var dropdown = document.getElementById("chatDropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }
    </script>
    <script src="./assets/js/all_user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>