<?php
session_start();
require_once 'lib/database.php';

// Ensure the user is staff
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php");
    exit();
}

// Initialize the database connection
$db = new database();
$pdo = $db->pdo;

// Fetch the logged-in staff ID
$staffID = $_SESSION['userid'];

// Fetch all tickets assigned to the logged-in staff
$sql = "SELECT * FROM tickets WHERE AssignedTo = :staffID ORDER BY CreatedAt DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':staffID', $staffID, PDO::PARAM_INT);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Tickets</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="adminpage">

<div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4>Staff Panel</h4>
                </div>
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./staff_dashboard.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-users"></i><a href="./staff_tickets.php" class="no-underline"><span >My Tickets</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-clipboard-list"></i><a href="./staff_tasks.php" class="no-underline"><span>My Tasks</span></a>
                    </div>


                </div>

                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6><?php echo htmlspecialchars($staffName); ?></h6>
                    <p>Staff</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

<!-- Main Content -->
<div class="col-md-9 col-lg-10 p-4" style="margin-left: 280px;">
    <!-- Page Title -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">Assigned Tickets</h2>
        <p class="text-muted">Manage and track your assigned tickets below.</p>
    </div>

    <!-- Tickets Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket) { ?>
                    <tr>
                        <!-- Ticket ID -->
                        <td class="fw-bold"><?php echo $ticket['TicketID']; ?></td>

                        <!-- Subject -->
                        <td><?php echo htmlspecialchars($ticket['Subject']); ?></td>

                        <!-- Status -->
                        <td>
                            <?php echo $ticket['Status'] == 'open' 
                                ? '<span class="badge bg-success">Open</span>' 
                                : '<span class="badge bg-primary">Resolved</span>'; ?>
                        </td>

                        <!-- Created At -->
                        <td><?php echo date('d M Y, h:i A', strtotime($ticket['CreatedAt'])); ?></td>

                        <!-- Actions -->
                        <td>
                            <!-- View Button -->
                            <button 
                                class="btn btn-info btn-sm view-ticket-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#viewTicketModal"
                                data-ticket-id="<?php echo $ticket['TicketID']; ?>"
                                data-subject="<?php echo htmlspecialchars($ticket['Subject']); ?>"
                                data-status="<?php echo $ticket['Status']; ?>"
                                data-created-at="<?php echo $ticket['CreatedAt']; ?>">
                                <i class="fas fa-eye"></i> View
                            </button>

                            <!-- Mark as Solved Button -->
                            <?php if ($ticket['Status'] == 'open') { ?>
                                <a href="resolve_ticket.php?ticket_id=<?php echo $ticket['TicketID']; ?>" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Mark as Solved
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="viewTicketModalLabel">Ticket Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <p><strong>Ticket ID:</strong> <span id="modal-ticket-id"></span></p>
                <p><strong>Subject:</strong> <span id="modal-subject"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
                <p><strong>Created At:</strong> <span id="modal-created-at"></span></p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// JavaScript to Populate Modal with Ticket Details
document.querySelectorAll('.view-ticket-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Get ticket details from data attributes
        const ticketID = this.getAttribute('data-ticket-id');
        const subject = this.getAttribute('data-subject');
        const status = this.getAttribute('data-status');
        const createdAt = this.getAttribute('data-created-at');

        // Populate modal fields
        document.getElementById('modal-ticket-id').textContent = ticketID;
        document.getElementById('modal-subject').textContent = subject;
        document.getElementById('modal-status').textContent = status === 'open' ? 'Open' : 'Resolved';
        document.getElementById('modal-created-at').textContent = createdAt;
    });
});
</script>

</body>
</html>
