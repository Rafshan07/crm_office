<?php
require 'database.php';
$db = new Database();

if (isset($_GET['id'])) {
    $opportunityID = $_GET['id'];

    $stmt = $db->pdo->prepare("DELETE FROM opportunity WHERE opportunityID = ?");
    if ($stmt->execute([$opportunityID])) {
        header("Location: ../opprtunity.php?success=Opportunity deleted successfully!");
        exit();
    } else {
        echo "Error deleting opportunity!";
    }
} else {
    echo "Invalid request!";
}
