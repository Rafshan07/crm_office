<?php
session_start();
require_once 'lib/database.php';
require_once 'lib/user.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php");
    exit();
}

// Create a database object
$db = new database();

// Fetch tickets assigned to the staff member
$staffID = $_SESSION['userid']; // Get staff ID from session
$getAssignedTicketsQuery = "SELECT * FROM tickets WHERE AssignedTo = :staffID";
$assignedTicketsStmt = $db->select($getAssignedTicketsQuery, ['staffID' => $staffID]);

// Convert PDOStatement to array
$assignedTickets = $assignedTicketsStmt ? $assignedTicketsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Fetch assigned tasks for the staff
$getAssignedTasksQuery = "SELECT * FROM task WHERE AssignedTo = :staffID";
$assignedTasksStmt = $db->select($getAssignedTasksQuery, ['staffID' => $staffID]);

// Convert PDOStatement to array
$assignedTasks = $assignedTasksStmt ? $assignedTasksStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Get total tickets and tasks
$totalTickets = count($assignedTickets);
$totalTasks = count($assignedTasks);

// Get the staff name
$user = new User();
$staffData = $user->getUserById($staffID);
$staffName = isset($staffData['name']) ? htmlspecialchars($staffData['name'], ENT_QUOTES, 'UTF-8') : 'Unknown Staff';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="staffpage">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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
                        <i class="fas fa-users"></i><a href="./staff_tickets.php" class="no-underline"><span>My Tickets</span></a>
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

            <!-- Main Staff Dashboard Content -->
            <div class="col-md-9 col-lg-10 p-4 right">
    <!-- Welcome Section -->
    <div class="mb-4">
        <h2 class="fw-bold">Welcome, <?php echo htmlspecialchars($staffName); ?>!</h2>
        <p class="text-muted">Here is your dashboard overview.</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-primary">Assigned Tickets</h5>
                        <p class="card-text fs-5 fw-bold">Total Tickets: <?php echo $totalTickets; ?></p>
                    </div>
                    <i class="fas fa-clipboard-list text-primary fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-success">Assigned Tasks</h5>
                        <p class="card-text fs-5 fw-bold">Total Tasks: <?php echo $totalTasks; ?></p>
                    </div>
                    <i class="fas fa-tasks text-success fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Tickets Section -->
    <div class="mt-5">
        <h3 class="fw-bold">Your Assigned Tickets</h3>
        <table class="table table-bordered table-hover mt-3 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignedTickets as $ticket) { ?>
                    <tr>
                        <td><?php echo $ticket['TicketID']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['Subject']); ?></td>
                        <td><?php echo $ticket['Status'] == 'open' ? '<span class="badge bg-success">Open</span>' : '<span class="badge bg-primary">Resolved</span>'; ?></td>
                        <td><a href="#" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Assigned Tasks Section -->
    <div class="mt-5">
        <h3 class="fw-bold">Your Assigned Tasks</h3>
        <table class="table table-bordered table-hover mt-3 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Task ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignedTasks as $task) { ?>
                    <tr>
                        <td><?php echo $task['TaskID']; ?></td>
                        <td><?php echo htmlspecialchars($task['Description']); ?></td>
                        <td><?php echo $task['Status'] == 'completed' ? '<span class="badge bg-success">Completed</span>' : '<span class="badge bg-warning">Pending</span>'; ?></td>
                        <td><a href="#" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>



    </div>

    <!-- Scripts -->
    <!-- Add your JS files here -->
</body>

</html>