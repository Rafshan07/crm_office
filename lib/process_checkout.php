<?php
session_start();

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
    exit;
}

// Retrieve customer details from the form (you may fetch them from session if logged in)
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$company = $_POST['company']; // Optional field, you may add this if required
$industry = $_POST['industry']; // Optional field

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $product) {
    $total_price += $product['price'] * $product['quantity'];
}

// Connect to the database
include('db_connection.php'); // Assuming you have a database connection file

// Step 1: Insert into the 'customer' table (if new customer)
$sql = "SELECT * FROM customer WHERE Email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$customer = $stmt->fetch();

if (!$customer) {
    // New customer, insert into the customer table
    $sql = "INSERT INTO customer (Name, Email, Phone, Address, Company, Industry, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $phone, $address, $company, $industry, '']);  // Assuming no password required at checkout
    $customer_id = $pdo->lastInsertId();
} else {
    // Existing customer, use the existing customer ID
    $customer_id = $customer['CustomerID'];
}

// Step 2: Insert into the 'order' table
$sql = "INSERT INTO `order` (OrderDate, Status, TotalAmount, CustomerID) VALUES (NOW(), 'pending', ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$total_price, $customer_id]);
$order_id = $pdo->lastInsertId();  // Get the last inserted OrderID

// Step 3: Insert into the 'order_details' table for each product in the cart
foreach ($_SESSION['cart'] as $product_id => $product) {
    // Check if there's enough stock before proceeding with the order
    $sql = "SELECT StockLevel FROM product WHERE ProductID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);
    $product_stock = $stmt->fetchColumn();

    if ($product_stock >= $product['quantity']) {
        // Insert into order_details table
        $sql = "INSERT INTO order_details (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id, $product_id, $product['quantity'], $product['price']]);

        // Reduce the stock level of the product
        $new_stock_level = $product_stock - $product['quantity'];
        $sql = "UPDATE product SET StockLevel = ? WHERE ProductID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_stock_level, $product_id]);
    } else {
        echo "Not enough stock for product: " . htmlspecialchars($product['name']);
        exit;
    }
}

// Step 4: Clear the cart after successful checkout
unset($_SESSION['cart']);

// Redirect to the order confirmation page
header("Location: checkout.php?order_id=$order_id");
exit;
?>
