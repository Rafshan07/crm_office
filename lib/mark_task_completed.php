<?php
session_start();
require_once 'database.php'; // Include the database connection file

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit();
}

// Check if the task ID is provided
if (isset($_GET['task_id'])) {
    $taskID = intval($_GET['task_id']); // Sanitize the task ID

    // Create a database object
    $db = new database();
    $pdo = $db->pdo;

    try {
        // Update the task status to 'completed'
        $sql = "UPDATE task SET Status = 'completed' WHERE TaskID = :taskID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':taskID', $taskID, PDO::PARAM_INT);
        $stmt->execute();

        // Set a success message and redirect back to the tasks page
        $_SESSION['message'] = "Task marked as completed successfully.";
        header("Location: ../staff_tasks.php");
        exit();
    } catch (PDOException $e) {
        // Handle query failure
        $_SESSION['message'] = "Error updating task: " . $e->getMessage();
        header("Location: ../staff_tasks.php");
        exit();
    }
} else {
    // If no task ID is provided, redirect back with an error message
    $_SESSION['message'] = "No task ID provided.";
    header("Location: ../staff_tasks.php");
    exit();
}
