<?php
require 'database.php';
$db = new Database();

if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Delete the lead
    $stmt = $db->pdo->prepare("DELETE FROM product WHERE ProductID = ?");
    if ($stmt->execute([$productID])) {
        header("Location: ../add_product.php?success=Product deleted successfully!");
        exit();
    } else {
        echo "Error deleting lead!";
    }
} else {
    echo "Invalid request!";
}
