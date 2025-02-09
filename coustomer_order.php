<?php
session_start();
require_once 'lib/database.php';
require_once 'lib/user.php'; // Include the user class

// Check if the user is logged in and if customer_id is set in the session
if (!isset($_SESSION['customer_id'])) {
    die("Error: Customer ID not found in session. Please log in.");
}

// Initialize the database object
$db = new database();
$customer_id = $_SESSION['customer_id']; // Get the customer ID from the session

// Fetch the orders for the customer
try {
    $query = "SELECT o.OrderID, o.OrderDate, o.Status, o.TotalAmount, 
                     p.Name AS ProductName, od.Quantity, od.Price 
              FROM `order` o
              JOIN orderdetails od ON o.OrderID = od.OrderID
              JOIN product p ON od.ProductID = p.ProductID
              WHERE o.CustomerID = :customer_id";

    // Fetch orders
    $orders = $db->select($query, [':customer_id' => $customer_id]);
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Apicorn CRM - Order History</title>

    <style>
        /* Sidebar */
        #nav-bar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: white;
        }

        #nav-bar a {
            color: white;
            text-decoration: none;
        }

        #nav-content .nav-button {
            padding: 10px;
            font-size: 16px;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            /* Adjust to fit sidebar */
            padding: 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            padding: 12px;
            vertical-align: middle;
            text-align: center;
        }

        .badge {
            font-size: 14px;
            padding: 5px 10px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
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
                    <h4> &nbsp;cous <br> &nbsp;tomer</h4>
                </div>
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i>
                        <a href="./customer.php" class="no-underline"><span>Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-brands fa-android"></i>
                        <span class="ms-2">Product</span>
                        <div class="submenu">
                            <a href="./copro.php" class="no-underline">
                                <div>All Products</div>
                            </a>
                            <a href="./customer_order.php" class="no-underline">
                                <div>Order Details</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./message.php" class="nav-link">
                            <i class="fa-regular fa-envelope"></i>
                            <span>Message & Support</span>
                        </a>
                    </div>
                    <div class="nav-button">
                        <a href="lib/logout.php" class="no-underline">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="order-history-section mt-4">
                    <div class="card shadow-lg rounded">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Order History</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-muted">Your Past Orders</h6>
                            <?php if ($orders): ?>
                                <table class="table table-hover table-bordered text-center align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total Amount</th>
                                            <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><strong>#<?= htmlspecialchars($order['OrderID']) ?></strong></td>
                                                <td><?= htmlspecialchars($order['OrderDate']) ?></td>
                                                <td>
                                                    <?php
                                                    $status = strtolower($order['Status']);
                                                    $status_icon = [
                                                        'pending' => '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>',
                                                        'completed' => '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Completed</span>',
                                                        'canceled' => '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Canceled</span>'
                                                    ];
                                                    echo $status_icon[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
                                                    ?>
                                                </td>
                                                <td><strong>$<?= number_format($order['TotalAmount'], 2) ?></strong></td>
                                                <td class="text-start">
                                                    <ul class="list-unstyled mb-0">
                                                        <li><i class="fas fa-box"></i> <?= htmlspecialchars($order['ProductName']) ?>
                                                            <small class="text-muted">(Qty: <?= htmlspecialchars($order['Quantity']) ?>, Price: $<?= htmlspecialchars($order['Price']) ?>)</small>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted text-center">No orders found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div> <!-- End Main Content -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
    <script src="./assets/js/customerdata.js"></script>
</body>

</html>