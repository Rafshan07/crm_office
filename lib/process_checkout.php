<?php
session_start();
require_once 'database.php'; // Include the database connection

// Instantiate the database class (assuming $pdo is already set up in database.php)
$db = new Database(); // Assuming the connection is set up in the Database class
$pdo = $db->pdo; // Access the $pdo property directly (assuming it's public or accessible)

// Check if session is already active
if (!isset($_SESSION['userid'])) {
    echo "You need to be logged in to place an order.";
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
    exit;
}

$customer_id = $_SESSION['userid']; // Get customer ID from session

// Calculate total price of items in the cart
$total_price = 0;
foreach ($_SESSION['cart'] as $product) {
    $total_price += $product['price'] * $product['quantity'];
}

// Insert the order into the database
$sql = "INSERT INTO `order` (OrderDate, Status, TotalAmount, CustomerID) VALUES (NOW(), 'pending', ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$total_price, $customer_id]);

// Get the order ID
$order_id = $pdo->lastInsertId();

// Insert the order details into the orderdetails table
foreach ($_SESSION['cart'] as $product_id => $product) {
    $quantity = $product['quantity'];
    $price = $product['price'];

    // Check product stock level before placing the order
    $sql = "SELECT StockLevel FROM product WHERE ProductID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product_stock = $stmt->fetchColumn();

    if ($product_stock >= $quantity) {
        // Insert order details into the database
        $sql = "INSERT INTO orderdetails (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id, $product_id, $quantity, $price]);

        // Update product stock
        $new_stock_level = $product_stock - $quantity;
        $sql = "UPDATE product SET StockLevel = ? WHERE ProductID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_stock_level, $product_id]);
    } else {
        echo "Not enough stock for product: " . htmlspecialchars($product['name']);
        exit;
    }
}

// Clear the cart after the order is placed
unset($_SESSION['cart']);

// Redirect to the order confirmation page with the order ID
header("Location: ../order_confirmation.php?order_id=" . $order_id);
exit;
