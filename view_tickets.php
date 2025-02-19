<?php
session_start();
require_once 'lib/database.php';

// Ensure the user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Initialize the database connection
$db = new database();
$pdo = $db->pdo; // Use the PDO connection from the database class

// Fetch all tickets from the database
try {
    $sql = "SELECT TicketID, CustomerID, Subject, Status, CreatedAt, AssignedTo, Description FROM tickets ORDER BY CreatedAt DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching tickets: " . $e->getMessage());
}

// Fetch all staff members from the user table (assuming the role is 'staff')
try {
    $staffSql = "SELECT UserID, Name FROM user WHERE Role = 'staff'"; // Use the `user` table for staff with role 'staff'
    $staffStmt = $pdo->prepare($staffSql);
    $staffStmt->execute();
    $staffList = $staffStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching staff: " . $e->getMessage());
}

// Fetch customer name by CustomerID from the customer table
function getCustomerName($customerID)
{
    global $pdo;
    try {
        $sql = "SELECT Name FROM customer WHERE CustomerID = :customerID"; // Fetching customer name from the `customer` table
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['Name'] : 'Unknown Customer';
    } catch (PDOException $e) {
        return 'Error fetching customer name';
    }
}

// Fetch staff name by StaffID from the user table
function getStaffName($staffID)
{
    global $pdo;
    try {
        $sql = "SELECT Name FROM user WHERE UserID = :staffID AND Role = 'staff'"; // Fetching staff name from `user` table where the role is 'staff'
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':staffID', $staffID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['Name'] : 'Unknown Staff';
    } catch (PDOException $e) {
        return 'Error fetching staff name';
    }
}

// Assign ticket to staff (Admin action)
if (isset($_POST['assign_ticket_id']) && isset($_POST['staff_id'])) {
    $ticketID = $_POST['assign_ticket_id'];
    $staffID = $_POST['staff_id'];

    try {
        $sql = "UPDATE tickets SET AssignedTo = :staffID WHERE TicketID = :ticketID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':staffID', $staffID, PDO::PARAM_INT);
        $stmt->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Ticket successfully assigned to staff.";
        } else {
            $_SESSION['message'] = "Failed to assign the ticket.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error assigning ticket: " . $e->getMessage();
    }

    // Redirect to refresh the page and show the message
    header("Location: view_tickets.php");
    exit();
}

// Edit staff assignment (Admin action)
if (isset($_POST['edit_ticket_id']) && isset($_POST['new_staff_id'])) {
    $ticketID = $_POST['edit_ticket_id'];
    $newStaffID = $_POST['new_staff_id'];

    try {
        $sql = "UPDATE tickets SET AssignedTo = :newStaffID WHERE TicketID = :ticketID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newStaffID', $newStaffID, PDO::PARAM_INT);
        $stmt->bindParam(':ticketID', $ticketID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Ticket reassigned to new staff.";
        } else {
            $_SESSION['message'] = "Failed to reassign the ticket.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error reassigning ticket: " . $e->getMessage();
    }

    // Redirect to refresh the page and show the message
    header("Location: view_tickets.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - View Tickets</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="adminpage">
    <div class="container-fluid">
        <div class="row">

            <!-- Admin Sidebar -->
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4>Admin Panel</h4>
                </div>
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./main.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                        <div class="submenu">
                            <a href="./_manage_users_create.php" class="no-underline">
                                <div>Create</div>
                            </a>
                            <a href="./upgradefrom.php" class="no-underline">
                                <div>All Users</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-chart-bar"></i>
                        <span>View Reports</span>
                        <div class="submenu">
                            <a href="./all_repot.php" class="no-underline">
                                <div>All Reports</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                        <div class="submenu">
                            <a href="./_products_new.php" class="no-underline">
                                <div>Our Product</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./view_tickets.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Ticket</span>&nbsp;
                        </a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-solid fa-file-invoice"></i>
                        <span>Invoice</span>
                        <div class="submenu">
                            <a href="./invoice.php" class="no-underline">
                                <div>Generatte Invoice</div>
                            </a>
                            <a href="./all_invoice.php" class="no-underline">
                                <div>All Invoice</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6><?php echo htmlspecialchars($loggedInUserName); ?></h6>
                    <p>Admin</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <style>
                form {
                    background: 0;
                    padding: 6px;
                    box-shadow: 0 0px 0px rgb(255, 255, 255);
                }
            </style>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10" style="margin-left: 260px;">
                <h2>Tickets</h2>

                <!-- Success/Error message display -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info">
                        <?php echo $_SESSION['message']; ?>
                        <?php unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Customer Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $ticket) {
                            $customerName = getCustomerName($ticket['CustomerID']);
                            $assignedToName = $ticket['AssignedTo'] ? getStaffName($ticket['AssignedTo']) : 'Not Assigned';
                        ?>
                            <tr>
                                <td><?php echo $ticket['TicketID']; ?></td>
                                <td><?php echo $customerName; ?></td>
                                <td><?php echo $ticket['Subject']; ?></td>
                                <td>
                                    <?php
                                    echo $ticket['Status'] == 'open' ? '<span class="badge bg-success">Open</span>' :
                                        '<span class="badge bg-danger">Resolved</span>';
                                    ?>
                                </td>
                                <td><?php echo $ticket['CreatedAt']; ?></td>
                                <td id="assigned-to-<?php echo $ticket['TicketID']; ?>"><?php echo $assignedToName; ?></td>
                                <td>
                                    <!-- View Button to trigger modal -->
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ticketModal-<?php echo $ticket['TicketID']; ?>">View</button>

                                    <!-- Only Admin can assign staff -->
                                    <?php if ($_SESSION['role'] === 'admin' && !$ticket['AssignedTo']) { ?>
                                        <form method="POST" action="view_tickets.php" class="d-inline">
                                            <select name="staff_id" class="form-select form-select-sm" required>
                                                <option value="">Select Staff</option>
                                                <?php foreach ($staffList as $staff) { ?>
                                                    <option value="<?php echo $staff['UserID']; ?>"><?php echo $staff['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="assign_ticket_id" value="<?php echo $ticket['TicketID']; ?>" />
                                            <button type="submit" class="btn btn-warning btn-sm">Assign to Staff</button>
                                        </form>
                                    <?php } ?>

                                    <!-- Edit Assignment for Admin -->
                                    <?php if ($_SESSION['role'] === 'admin' && $ticket['AssignedTo']) { ?>
                                        <form method="POST" action="view_tickets.php" class="d-inline">
                                            <select name="new_staff_id" class="form-select form-select-sm" required>
                                                <option value="">Select New Staff</option>
                                                <?php foreach ($staffList as $staff) { ?>
                                                    <option value="<?php echo $staff['UserID']; ?>"><?php echo $staff['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="edit_ticket_id" value="<?php echo $ticket['TicketID']; ?>" />
                                            <button type="submit" class="btn btn-info btn-sm">Edit Assignment</button>
                                        </form>
                                    <?php } ?>

                                    <!-- Delete Button for Admin -->
                                    <a href="view_tickets.php?delete_ticket_id=<?php echo $ticket['TicketID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>

                            <!-- Modal for viewing ticket details -->
                            <div class="modal fade" id="ticketModal-<?php echo $ticket['TicketID']; ?>" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ticketModalLabel">Ticket #<?php echo $ticket['TicketID']; ?> Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($ticket['Subject']); ?></p>
                                            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($ticket['Description'])); ?></p>
                                            <p><strong>Status:</strong> <?php echo $ticket['Status'] == 'open' ? 'Open' : 'Resolved'; ?></p>
                                            <p><strong>Created At:</strong> <?php echo $ticket['CreatedAt']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>