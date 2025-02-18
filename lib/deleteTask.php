<?php
require 'database.php';
$db = new Database();

if (isset($_GET['id'])) {
    $taskID = $_GET['id'];

    $stmt = $db->pdo->prepare("DELETE FROM task WHERE TaskID = ?");
    if ($stmt->execute([$taskID])) {
        header("Location: ../sales_tasks.php?success=Task deleted successfully!");
        exit();
    } else {
        echo "Error deleting Task!";
    }
} else {
    echo "Invalid request!";
}
