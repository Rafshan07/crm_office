<?php
session_start();
require_once 'lib/database.php';

// Check if the user is an admin or staff
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: index.php");
    exit();
}

$ticketID = $_GET['ticket_id'];

// Initialize the database connection
$db = new database();
$pdo = $db->pdo; // Use the PDO connection from the database class

// Fetch ticket details from the database using PDO
try {
    $sql = "SELECT * FROM tickets WHERE TicketID = :ticketID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo "Ticket not found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error fetching ticket: " . $e->getMessage();
    exit();
}

// Mark ticket as done (for staff)
if ($_SESSION['role'] === 'staff' && isset($_GET['mark_as_done_ticket_id']) && $ticket['TicketID'] == $_GET['mark_as_done_ticket_id']) {
    try {
        $updateSql = "UPDATE tickets SET Status = 'resolved' WHERE TicketID = :ticketID";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
        if ($updateStmt->execute()) {
            $_SESSION['message'] = "Ticket marked as solved.";
            header("Location: view_ticket_details.php?ticket_id=" . $ticketID);
            exit();
        } else {
            $_SESSION['message'] = "Failed to mark ticket as solved.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error updating ticket: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="text-center mb-4">Ticket Details</h2>

        <!-- Success/Error message display -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p><strong>Subject:</strong> <?php echo htmlspecialchars($ticket['Subject']); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($ticket['Description'])); ?></p>
                <p><strong>Status:</strong> 
                    <?php echo $ticket['Status'] == 'open' ? 
                        '<span class="badge bg-success">Open</span>' : 
                        '<span class="badge bg-danger">Resolved</span>'; ?>
                </p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($ticket['CreatedAt']); ?></p>
            </div>
        </div>

        <!-- Mark as Solved Button (for Staff only) -->
        <?php if ($_SESSION['role'] === 'staff' && $ticket['Status'] == 'open'): ?>
            <a href="view_ticket_details.php?ticket_id=<?php echo $ticket['TicketID']; ?>&mark_as_done_ticket_id=<?php echo $ticket['TicketID']; ?>" class="btn btn-warning btn-lg w-100">Mark as Solved</a>
        <?php endif; ?>

        <!-- Close Button to go back -->
        <a href="view_tickets.php" class="btn btn-secondary btn-lg w-100 mt-3">Close</a>
    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
