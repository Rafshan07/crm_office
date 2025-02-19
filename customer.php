<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$db = new database();
$customer_name = "Guest"; // Default value

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];

    // Fetch customer name from customer table
    $query = "SELECT Name FROM customer WHERE CustomerID = ?";
    $customer = $db->select($query, [$user_id]);

    // Fetch customer name if exists, otherwise set as "Unknown Customer"
    $customer_name = ($customer && $customer->rowCount() > 0) ? $customer->fetch(PDO::FETCH_ASSOC)['Name'] : "Unknown Customer";

    // Fetch customer orders
    $query = "SELECT o.OrderID, o.OrderDate, o.Status, o.TotalAmount 
              FROM `order` o 
              WHERE o.CustomerID = ?";
    $orders = $db->select($query, [$user_id])->fetchAll(PDO::FETCH_ASSOC);

    // Fetch details of each order
    $orderDetails = [];
    if ($orders && count($orders) > 0) {
        foreach ($orders as $order) {
            $orderId = $order['OrderID'];
            $query = "SELECT od.OrderDetailID, od.Quantity, od.Price, p.Name AS ProductName, p.Category 
                      FROM `orderdetails` od 
                      JOIN `product` p ON od.ProductID = p.ProductID
                      WHERE od.OrderID = ?";
            $orderDetails[$orderId] = $db->select($query, [$orderId])->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Customer Dashboard</title>
</head>

<body>
    <div class="container-fluid">
        <!-- Sidebar -->
        <div class="row">
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4>Customer</h4>
                </div>
                <!-- Menu Items -->
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./customer.php" class="no-underline"><span class="dashboard">Dashboard</span></a>
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
                        <a href="./submit_ticket.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Ticket</span>
                        </a>
                    </div>
                    <div class="nav-button">
                        <a href="./message.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Message and</span>&nbsp;
                            <i class="fa-solid fa-headset"></i>
                            <span class="ms-2">Support</span>
                        </a>
                    </div>
                </div>

                <!-- Footer Section -->
                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6><?php echo $customer_name; ?></h6>
                    <p>Customer</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10" style="padding-left: 250px;">
                <div class="container py-4">
                    <h1 class="text-primary mb-4">Welcome, <?php echo $customer_name; ?>!</h1>

                    <h2 class="text-secondary mb-4">Your Orders</h2>

                    <?php if (isset($orders) && count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order mb-4 p-4 border rounded shadow-sm bg-light">
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="text-dark">Order #<?php echo $order['OrderID']; ?></h3>

                                    <?php
                                    // Change badge color based on order status
                                    $statusClass = '';
                                    switch ($order['Status']) {
                                        case 'Cancelled':
                                            $statusClass = 'bg-danger'; // Red for cancelled
                                            break;
                                        case 'Completed':
                                            $statusClass = 'bg-success'; // Green for completed
                                            break;
                                        case 'Pending':
                                            $statusClass = 'bg-warning'; // Yellow for pending
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary'; // Default gray
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?> p-2"><?php echo $order['Status']; ?></span>
                                </div>

                                <p><strong>Order Date:</strong> <span class="text-muted"><?php echo $order['OrderDate']; ?></span></p>
                                <p><strong>Total Amount:</strong> <span class="text-primary fs-4">$<?php echo number_format($order['TotalAmount'], 2); ?></span></p>

                                <h4 class="text-secondary mt-4">Order Details:</h4>
                                <table class="table table-bordered table-striped table-hover shadow-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($orderDetails[$order['OrderID']]) && count($orderDetails[$order['OrderID']]) > 0) {
                                            foreach ($orderDetails[$order['OrderID']] as $detail) {
                                                echo "<tr>
                                                    <td>{$detail['ProductName']}</td>
                                                    <td>{$detail['Category']}</td>
                                                    <td>{$detail['Quantity']}</td>
                                                    <td>$" . number_format($detail['Price'], 2) . "</td>
                                                    <td>$" . number_format($detail['Quantity'] * $detail['Price'], 2) . "</td>
                                                  </tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">You have not placed any orders yet.</p>
                    <?php endif; ?>
                </div>
            </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="./assets/js/nav.js"></script>

</html>