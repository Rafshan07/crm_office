<?php

session_start();
require_once 'database.php';


$data = json_decode(file_get_contents('php://input'), true);
$invoiceId = $data['invoiceId'] ?? null;

if ($invoiceId) {
    $db = new database();
    $query = "DELETE FROM invoices WHERE id = :id";
    $stmt = $db->pdo->prepare($query);
    $stmt->bindParam(':id', $invoiceId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete invoice']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
