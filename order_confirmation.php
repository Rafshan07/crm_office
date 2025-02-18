<?php
session_start();
require_once 'lib/database.php';

if (!isset($_GET['order_id'])) {
    die('Order ID is missing!');
}

$order_id = $_GET['order_id'];
$db = new database();

$query = "SELECT o.OrderID, o.OrderDate, o.Status, o.TotalAmount, c.Name AS CustomerName
          FROM `order` o
          JOIN customer c ON o.CustomerID = c.CustomerID
          WHERE o.OrderID = ?";
$params = [$order_id];
$order = $db->select($query, $params);

if (!$order) {
    die('Order not found!');
}

$order = $order->fetch(PDO::FETCH_ASSOC);

$query = "SELECT p.Name AS ProductName, od.Quantity, od.Price
          FROM orderdetails od
          JOIN product p ON od.ProductID = p.ProductID
          WHERE od.OrderID = ?";
$order_details = $db->select($query, [$order_id]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Order Confirmation</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Order Confirmation</h2>

        <div class="mb-4">
            <h4>Order Details</h4>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($order['OrderID']) ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['OrderDate']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($order['Status']) ?></p>
            <p><strong>Customer Name:</strong> <?= htmlspecialchars($order['CustomerName']) ?></p>
            <p><strong>Total Amount:</strong> ৳<?= number_format($order['TotalAmount'], 2) ?></p>
        </div>

        <h4>Order Items</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (per unit)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $order_details->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ProductName']) ?></td>
                        <td><?= htmlspecialchars($item['Quantity']) ?></td>
                        <td>৳<?= number_format($item['Price'], 2) ?></td>
                        <td>৳<?= number_format($item['Quantity'] * $item['Price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="customer.php" class="btn btn-primary mt-3">Back to Homepage</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
