<?php
require 'database.php';
$db = new database();

if (isset($_GET['CustomerID'])) {
    $customerID = $_GET['CustomerID'];

    $query = "SELECT * FROM customer WHERE CustomerID = ?";
    $stmt = $db->pdo->prepare($query);
    $stmt->execute([$customerID]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        die("Customer not found!");
    }
} else {
    die("No customer ID provided!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $company = $_POST['company'];
    $industry = $_POST['industry'];

    $updateQuery = "UPDATE customer SET Name=?, Email=?, Phone=?, Address=?, Company=?, Industry=? WHERE CustomerID=?";
    $updateStmt = $db->pdo->prepare($updateQuery);
    $success = $updateStmt->execute([$name, $email, $phone, $address, $company, $industry, $customerID]);

    if ($success) {
        echo "<script>alert('Customer updated successfully!'); window.location.href='upgradefrom.php';</script>";
        header("Location: ../upgradefrom.php");
    } else {
        echo "<script>alert('Update failed!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Customer Details</h2>
        <form method="post">
            <input type="hidden" name="customerid" value="<?php echo $customerID; ?>">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($customer['Name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($customer['Email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($customer['Phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($customer['Address']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Company</label>
                <input type="text" name="company" class="form-control" value="<?php echo htmlspecialchars($customer['Company']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Industry</label>
                <input type="text" name="industry" class="form-control" value="<?php echo htmlspecialchars($customer['Industry']); ?>">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="upgradefrom.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>