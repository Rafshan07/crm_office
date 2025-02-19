<?php
session_start();

// Include the database connection file
require_once 'lib/database.php';

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php");
    exit();
}

// Create an instance of the database class
$db = new database();
$pdo = $db->pdo; // Use the PDO connection from the database class

// Fetch the staff ID from the session (make sure it's stored during login)
$staffID = $_SESSION['userid'];  // Assuming the staff ID is stored in the session as 'userid'

// Fetch tasks assigned to the current staff member
try {
    $sql = "SELECT TaskID, Description, DueDate, Status FROM task WHERE AssignedTo = :staffID ORDER BY DueDate DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':staffID', $staffID, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all tasks assigned to the logged-in staff member
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle query failure
    $_SESSION['message'] = "Error fetching tasks: " . $e->getMessage();
    header("Location: staff_dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff - Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
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

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4" style="margin-left: 280px;">
                <!-- Page Title -->
                <div class="mb-4 text-center">
                    <h2 class="fw-bold text-primary">Assigned Tasks</h2>
                    <p class="text-muted">Easily manage and track your assigned tasks below.</p>
                </div>

                <!-- Display Success or Error Messages -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <?php echo htmlspecialchars($_SESSION['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <!-- Tasks Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover align-middle shadow-sm">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Task ID</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tasks)): ?>
                                <!-- No Tasks Row -->
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No tasks assigned.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <!-- Task ID -->
                                        <td class="fw-bold text-center"><?php echo htmlspecialchars($task['TaskID']); ?></td>

                                        <!-- Description -->
                                        <td><?php echo htmlspecialchars($task['Description']); ?></td>

                                        <!-- Due Date -->
                                        <td class="text-center"><?php echo date('d M Y', strtotime($task['DueDate'])); ?></td>

                                        <!-- Status -->
                                        <td class="text-center">
                                            <?php if ($task['Status'] == 'completed'): ?>
                                                <span class="badge bg-success px-3 py-2">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning px-3 py-2">Pending</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <?php if ($task['Status'] == 'pending'): ?>
                                                <a href="lib/mark_task_completed.php?task_id=<?php echo htmlspecialchars($task['TaskID']); ?>"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Mark as Completed
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>