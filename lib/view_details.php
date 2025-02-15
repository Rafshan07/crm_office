<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "Order ID is missing.";
    exit();
}

$order_id = $_GET['order_id'];
$db = new database();

// Fetch order details by order_id
$query = "SELECT o.OrderID, o.OrderDate, o.Status, o.TotalAmount, c.Name, c.CustomerID, c.Phone 
          FROM `order` o 
          JOIN customer c ON o.CustomerID = c.CustomerID
          WHERE o.OrderID = ?";
$order_stmt = $db->select($query, [$order_id]);

// Fetch order items (if any) for detailed view, including product name
$query_order_items = "SELECT oi.OrderDetailID, oi.ProductID, oi.Quantity, oi.Price, p.Name 
                      FROM orderdetails oi 
                      JOIN product p ON oi.ProductID = p.ProductID 
                      WHERE oi.OrderID = ?";
$order_items_stmt = $db->select($query_order_items, [$order_id]);

if (!$order_stmt) {
    echo "Order not found.";
    exit();
}

// Fetch the first order detail
$order_details = $order_stmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items (if any)
$order_items = $order_items_stmt ? $order_items_stmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Debugging: Check POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);  // Debugging POST data
    exit();
}

// Handle Approve Action
if (isset($_POST['action']) && $_POST['action'] === 'approve') {
    // Debugging: Check if Approve button was clicked
    echo "Approve button clicked.<br>";

    // Update the order status to 'completed'
    $query_update_status = "UPDATE `order` SET Status = 'completed' WHERE OrderID = ?";
    $db->update($query_update_status, [$order_id]);  // Executes the query

    // Redirect back to refresh the page with updated status
    header("Location: view_orders.php?order_id=$order_id");
    exit();
}

// Handle Delete Action
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Debugging: Check if Delete button was clicked
    echo "Delete button clicked.<br>";

    // Delete related order items first
    $query_delete_items = "DELETE FROM orderdetails WHERE OrderID = ?";
    $db->update($query_delete_items, [$order_id]);  // Executes the query

    // Then delete the order itself
    $query_delete_order = "DELETE FROM `order` WHERE OrderID = ?";
    $db->update($query_delete_order, [$order_id]);  // Executes the query

    // Redirect to order list after deletion
    header("Location: order_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Order Details</title>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                <h3>Order Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Order ID</th>
                        <td>#<?= $order_details['OrderID']; ?></td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td><?= $order_details['Name']; ?></td>
                    </tr>
                    <tr>
                        <th>Customer Phone</th>
                        <td><?= $order_details['Phone']; ?></td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td><?= $order_details['OrderDate']; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php
                            $status = strtolower($order_details['Status']);
                            $status_icon = [
                                'pending' => '<span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>',
                                'completed' => '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Completed</span>',
                                'canceled' => '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Canceled</span>'
                            ];
                            echo $status_icon[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>$<?= $order_details['TotalAmount']; ?></td>
                    </tr>
                    <tr>
                        <th>Customer ID</th>
                        <td><?= $order_details['CustomerID']; ?></td>
                    </tr>
                </table>

                <h4>Order Items</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($order_items): ?>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td><?= $item['Name']; ?></td>
                                    <td><?= $item['Quantity']; ?></td>
                                    <td>$<?= $item['Price']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No items found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Approve & Delete Buttons -->
                <!-- Approve & Delete Buttons -->
                <form class="d-flex justify-content-center " method="POST" style="padding:7px" id="orderActionForm">
                    <button type="submit" name="approve" class="btn btn-success pr-2" id="approveBtn">Approve Order</button>
                    <button type="submit" name="delete" class="btn btn-danger pl-2" id="deleteBtn">Delete Order</button>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>