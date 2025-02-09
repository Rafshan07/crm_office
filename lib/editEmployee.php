<?php
require 'database.php';
$db = new database();

if (isset($_GET['UserID'])) {
    $userID = $_GET['UserID'];

    $query = "SELECT * FROM User WHERE UserID = ?";
    $stmt = $db->pdo->prepare($query);
    $stmt->execute([$userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found!");
    }
} else {
    die("No User ID provided!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $department = $_POST['department'];

    $updateQuery = "UPDATE user SET Name=?, Email=?, role=?, Department=? WHERE UserID=?";
    $updateStmt = $db->pdo->prepare($updateQuery);
    $success = $updateStmt->execute([$name, $email, $role, $department, $userID]);

    if ($success) {
        echo "<script>alert('Employee updated successfully!'); window.location.href='upgradefrom.php';</script>";
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
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Employee Details</h2>
        <form method="post">
            <input type="hidden" name="userid" value="<?php echo $userID; ?>">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo ($user['Name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo ($user['Email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <input type="text" name="role" class="form-control" value="<?php echo ($user['role']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <input type="text" name="department" class="form-control" value="<?php echo ($user['Department']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="upgradefrom.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>