<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {
    header("Location: index.php");
    exit();
}

$db = new database();

$query = "SELECT o.OrderID, o.OrderDate, o.Status, o.TotalAmount, o.CustomerID, c.Name, c.Phone 
          FROM `order` o
          JOIN customer c ON o.CustomerID = c.CustomerID";
$orders = $db->select($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $order_id = intval($_POST['order_id']);

    if ($_POST['action'] === 'approve') {
        $query_update_status = "UPDATE `order` SET Status = 'Completed' WHERE OrderID = ?";
        $db->update($query_update_status, [$order_id]);
    } elseif ($_POST['action'] === 'cancel') {

        $query_cancel_status = "UPDATE `order` SET Status = 'Cancelled' WHERE OrderID = ?";
        $db->update($query_cancel_status, [$order_id]);
    }


    header("Location: order_details.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>ApCorn CRM</title>
</head>

<body class="adminpage">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4>Sales</h4>
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
                            <i class="fa-brands fa-codepen"></i><span class="ms-2">Opportunities</span>
                        </a>
                    </div>
                    <div class="nav-button">
                        <a href="./sales_tasks.php" class="nav-link d-flex align-items-center">
                            <i class="fa-solid fa-list-check"></i><span class="ms-2">tasks</span>
                        </a>
                    </div>
                    <a href="./customer_data.php" class="nav-button text-decoration-none">
                        <i class="fa-solid fa-database"></i><span class="ms-2">customer data</span></a>
                </div>
                <!-- Footer Section -->
                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6>Sales</h6>
                    <p>Sales</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- Order Table Section -->
                <div class="col-md-9 col-lg-10 p-4 table-wrapper right">
                    <h4>All Orders</h4>

                    <!-- Order Table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Total Amount</th>
                                <th>Customer ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($orders): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['OrderID']; ?></td>
                                        <td><?= $order['Name']; ?></td>
                                        <td>+88<?= $order['Phone']; ?></td>
                                        <td><?= $order['OrderDate']; ?></td>
                                        <td>
                                            <?php
                                            $status = strtolower($order['Status']);
                                            $status_icon = [
                                                'pending' => '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>',
                                                'completed' => '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Completed</span>',
                                                'cancelled' => '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Cancelled</span>' // Fixed typo here
                                            ];
                                            echo $status_icon[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
                                            ?>
                                        </td>
                                        <td>$<?= $order['TotalAmount']; ?></td>
                                        <td><?= $order['CustomerID']; ?></td>
                                        <td>
                                            <!-- View Details Button -->
                                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" data-order-id="<?= $order['OrderID']; ?>">View Details</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">No orders found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Order Details -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <!-- Order details will be dynamically loaded here -->
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderDetailsModal = document.getElementById('orderDetailsModal');
            orderDetailsModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const orderId = button.getAttribute('data-order-id');

                // Fetch the order details based on order_id
                fetch(`lib/view_details.php?order_id=${orderId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('orderDetailsContent').innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error fetching order details:', error);
                        document.getElementById('orderDetailsContent').innerHTML = 'Error loading order details.';
                    });
            });
        });
    </script>

</body>

</html>