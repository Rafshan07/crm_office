<?php
ob_start();
session_start();
require_once 'database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {
    header("Location: index.php");
    exit();
}

$db = new database();

if (isset($_GET['customerid'])) {
    $customerid = trim($_GET['customerid']);

    if (!is_numeric($customerid)) {
        echo "<script>alert('Invalid Customer ID!');</script>";
        exit();
    } else {
        // Fetch tasks related to this customer ID
        $query = "SELECT * FROM task WHERE RelatedCustomerID = :customerid";  // Ensure correct column name
        $stmt = $db->pdo->prepare($query);
        $stmt->bindParam(':customerid', $customerid, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the tasks
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    echo "Customer ID is missing!";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <title>Tasks for Customer ID: <?php echo htmlspecialchars($customerid); ?></title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tasks for Customer ID: <?php echo htmlspecialchars($customerid); ?></h2>

        <?php if ($tasks): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Task ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Assigned To</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['TaskID']); ?></td>
                                <td><?php echo htmlspecialchars($task['Description']); ?></td>
                                <td><?php echo htmlspecialchars($task['DueDate']); ?></td>
                                <td><?php echo htmlspecialchars($task['AssignedTo']); ?></td>
                                <td>
                                    <a href="editTask.php?id=<?php echo htmlspecialchars($task['TaskID']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="deleteTask.php?id=<?php echo htmlspecialchars($task['TaskID']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No tasks found for this customer.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
