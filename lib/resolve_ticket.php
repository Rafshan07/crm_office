<?php
session_start();
require_once 'database.php';

// Check if the user is an admin or staff
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: index.php");
    exit();
}

$ticketID = $_GET['ticket_id'];

// Update the ticket status to 'solved'
$sql = "UPDATE tickets SET Status = 'solved' WHERE TicketID = '$ticketID'";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: ../view_tickets.php");
    exit();
} else {
    echo "Error resolving the ticket.";
}
?>
