<?php
require 'database.php';
$db = new Database();

if (!isset($_GET['id'])) {
    die("No Task ID provided!");
}

$taskID = $_GET['id'];

// Fetch the task details from the database
$stmt = $db->pdo->prepare("SELECT * FROM task WHERE TaskID = ?");
$stmt->execute([$taskID]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    die("Task not found!");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $duedate = $_POST['dueDate'];
    $assignedto = $_POST['assignedto'];
    $relcustomerid = $_POST['customerId'];

    // Prepare and execute the update query
    $updateStmt = $db->pdo->prepare("UPDATE task SET Description=?, DueDate=?, AssignedTo=?, RelatedCustomerID=? WHERE TaskID=?");
    if ($updateStmt->execute([$description, $duedate, $assignedto, $relcustomerid, $taskID])) {
        header("Location: ../sales_tasks.php?success=Task updated successfully!");
    } else {
        echo "Error updating task!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Task</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .update-form-container {
            max-width: 600px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
        }

        h2 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #343a40;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="update-form-container mx-auto">
            <h2 class="text-center">Update Task</h2>
            <form method="POST">
                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3" required><?= htmlspecialchars($task['Description']) ?></textarea>
                </div>
                <!-- Due Date -->
                <div class="mb-3">
                    <label for="dueDate" class="form-label">Due Date</label>
                    <input type="date" name="dueDate" class="form-control" id="dueDate" value="<?= htmlspecialchars($task['DueDate']) ?>" required>
                </div>
                <!-- Assigned To -->
                <div class="mb-3">
                    <label for="assignedTo" class="form-label">Assigned To</label>
                    <input type="text" name="assignedto" class="form-control" id="assignedTo" value="<?= htmlspecialchars($task['AssignedTo']) ?>" required>
                </div>
                <!-- Customer ID -->
                <div class="mb-3">
                    <label for="customerId" class="form-label">Customer ID</label>
                    <input type="text" name="customerId" class="form-control" id="customerId" value="<?= htmlspecialchars($task['RelatedCustomerID']) ?>" required>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Update Task</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>