<?php
session_start();
require_once 'lib/database.php';
require_once 'lib/user.php';

// Check if customer is logged in and has a valid customer_id in session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: copro.php");  // Redirect if the cart is empty
    exit();
}

$db = new database();
$user = new user();

// Retrieve the customer's information
$customer_id = $_SESSION['userid'];  // Assuming customer_id is stored in the session after login

// Initialize the total amount variable
$total_amount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $total_amount = array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $_SESSION['cart']));
}

// Place the order
if (isset($_POST['place_order'])) {
    // Step 1: Insert into the 'order' table
    $order_date = date('Y-m-d H:i:s');  // Current date and time
    $status = 'Pending';  // Default order status

    // Insert the order into the 'order' table using prepared statement with named parameters
    $query = "INSERT INTO `order` (OrderDate, Status, TotalAmount, CustomerID) VALUES (:OrderDate, :Status, :TotalAmount, :CustomerID)";
    $params = [
        ':OrderDate' => $order_date,
        ':Status' => $status,
        ':TotalAmount' => $total_amount,
        ':CustomerID' => $customer_id
    ];
    $order_id = $db->insert($query, $params);  // Get the last inserted OrderID

    // Step 2: Insert order details into 'order_details'
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $quantity = $product['quantity'];
        $price = $product['price'];

        // Insert each item into the order details table
        $query = "INSERT INTO `orderdetails` (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
        $params = [$order_id, $product_id, $quantity, $price];
        $db->insert($query, $params);  // Insert into order_details

        // Step 3: Update product stock level
        $query = "UPDATE product SET StockLevel = StockLevel - ? WHERE ProductID = ?";
        $db->update($query, [$quantity, $product_id]);
    }

    // Step 4: Clear the cart after placing the order
    unset($_SESSION['cart']);

    // Redirect to a confirmation page or order details page
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Checkout</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Checkout</h2>

        <!-- Display Cart Summary -->
        <h4>Order Summary</h4>
        <ul class="list-group">
            <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($product['name']) ?> x <?= $product['quantity'] ?>
                    <span class="badge bg-primary">৳<?= $product['price'] * $product['quantity'] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="total-container mt-3">
            <h5><strong>Total: ৳<?= $total_amount ?></strong></h5>
        </div>

        <!-- Checkout Form -->
        <form method="POST" action="">
            <button type="submit" name="place_order" class="btn btn-success mt-3">Place Order</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
