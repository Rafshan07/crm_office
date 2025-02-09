<?php
require_once 'lib/database.php';

if (!isset($_GET['order_id'])) {
    echo json_encode([]);
    exit();
}

$order_id = $_GET['order_id'];
$db = new database();

// Query to fetch order details for the specific order
$query = "SELECT od.OrderDetailID, od.ProductID, od.Quantity, od.Price
          FROM `orderdetails` od
          WHERE od.OrderID = :order_id";

$order_details = $db->select($query, [':order_id' => $order_id]);

echo json_encode($order_details);
