<?php
// get_invoice_details.php
session_start();
require_once 'database.php';

$data = json_decode(file_get_contents('php://input'), true);
$invoiceId = $data['invoiceId'] ?? null;

if ($invoiceId) {
    $db = new database();
    $query = "SELECT * FROM invoices WHERE id = :id";
    $stmt = $db->pdo->prepare($query);
    $stmt->bindParam(':id', $invoiceId, PDO::PARAM_INT);
    $stmt->execute();
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($invoice) {
        // Send invoice details as a response
        echo json_encode(['success' => true, 'invoice' => $invoice]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
}
?>
