<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['userid'])) {
    die("Error: Customer ID not found in session. Please log in.");
}

$db = new database();
$customer_id = $_SESSION['userid'];

try {
    $query = "SELECT o.OrderID, o.OrderDate, o.Status, o.TotalAmount, 
                     p.Name AS ProductName, od.Quantity, od.Price 
              FROM `order` o
              JOIN orderdetails od ON o.OrderID = od.OrderID
              JOIN product p ON od.ProductID = p.ProductID
              WHERE o.CustomerID = :customer_id";

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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Apicorn CRM</title>

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
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4> &nbsp;cous <br> &nbsp;tomer</h4>
                </div>
                <!-- Menu Items -->
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./customer.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-brands fa-android"></i>
                        <span class="ms-2">Product</span>
                        <div class="submenu">
                            <a href="./copro.php" class="no-underline">
                                <div>all Product</div>
                            </a>
                            <a href="./coustomer_order.php" class="no-underline">
                                <div>order details</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./message.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Message and</span>&nbsp;
                            <i class="fa-solid fa-headset"></i>
                            <span class="ms-2">support</span>
                        </a>
                    </div>
                </div>

                <!-- Footer Section -->
                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6>Setuuuuuuuu</h6>
                    <p>sales</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
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
                                                'cancelled' => '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Canceled</span>'
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>