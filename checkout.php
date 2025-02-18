<?php
session_start();
require_once 'lib/database.php';
require_once 'lib/user.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: copro.php");
    exit();
}

$db = new database();
$user = new user();

$customer_id = $_SESSION['userid'];

$total_amount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $total_amount = array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $_SESSION['cart']));
}

if (isset($_POST['place_order'])) {
    $order_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    $query = "INSERT INTO `order` (OrderDate, Status, TotalAmount, CustomerID) VALUES (:OrderDate, :Status, :TotalAmount, :CustomerID)";
    $params = [
        ':OrderDate' => $order_date,
        ':Status' => $status,
        ':TotalAmount' => $total_amount,
        ':CustomerID' => $customer_id
    ];
    $order_id = $db->insert($query, $params);

    foreach ($_SESSION['cart'] as $product_id => $product) {
        $quantity = $product['quantity'];
        $price = $product['price'];

        $query = "INSERT INTO `orderdetails` (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
        $params = [$order_id, $product_id, $quantity, $price];
        $db->insert($query, $params);

        $query = "UPDATE product SET StockLevel = StockLevel - ? WHERE ProductID = ?";
        $db->update($query, [$quantity, $product_id]);
    }

    unset($_SESSION['cart']);

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
        <form method="POST" action="lib/process_checkout.php">
            <button type="submit" name="place_order" class="btn btn-success mt-3">Place Order</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>