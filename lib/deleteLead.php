<?php
require 'database.php';
$db = new Database();

if (isset($_GET['id'])) {
    $leadID = $_GET['id'];

    // Delete the lead
    $stmt = $db->pdo->prepare("DELETE FROM lead WHERE LeadID = ?");
    if ($stmt->execute([$leadID])) {
        header("Location: ../updatelead.php?success=Lead deleted successfully!");
        exit();
    } else {
        echo "Error deleting lead!";
    }
} else {
    echo "Invalid request!";
}
