<?php
require 'database.php';
$db = new Database();

if (!isset($_GET['id'])) {
    die("No Product ID provided!");
}

$productID = $_GET['id'];

$stmt = $db->pdo->prepare("SELECT * FROM product WHERE ProductID = ?");
$stmt->execute([$productID]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found!");
}

// Process form submission (update logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stocklevel = $_POST['stocklevel'];

    $updateStmt = $db->pdo->prepare("UPDATE product SET Name=?, Category=?, Price=?, StockLevel=? WHERE ProductID=?");
    if ($updateStmt->execute([$name, $category, $price, $stocklevel, $productID])) {
        header("Location: ../add_product.php?success=Product updated successfully!");
    } else {
        echo "Error updating product!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Product</h2>

        <form method="post" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['Name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['Category']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['Price']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock Level</label>
                <input type="number" name="stocklevel" class="form-control" value="<?= htmlspecialchars($product['StockLevel']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="add_product.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>