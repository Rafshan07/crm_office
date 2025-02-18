<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {

    header("Location: index.php");
    exit();
}
$db = new database();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT Name FROM user WHERE UserID = ?";
    $user = $db->select($query, [$user_id]);

    $user_name = $user ? $user[0]['Name'] : "Unknown User";
} else {
    $user_name = "Guest";
}

$error = '';
$success = '';

$db = new database();
if ($_POST) {
    $source = $_POST['source'];
    $status = $_POST['status'];
    $assignedTo = $_POST['assignedTo'];
    $createdDate = $_POST['createdDate'];
    $customerID = $_POST['customerID'];

    if ($source == '' || $status == '' || $assignedTo == '' || $createdDate == '' || $customerID == '') {
        $error = "Field empty!";
    } else {
        $query = "INSERT INTO lead (Source, Status, AssignedTo, CreateDate, CustomerID) 
                  VALUES (:source, :status, :assignedTo, :createdDate, :customerID)";
        $stmt = $db->pdo->prepare($query);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':assignedTo', $assignedTo);
        $stmt->bindParam(':createdDate', $createdDate);
        $stmt->bindParam(':customerID', $customerID);

        if ($stmt->execute()) {
            $success = "Lead Added Successfully!";
            header("Location: updatelead.php");
            exit();
        } else {
            $error = "Error executing query!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>apcorn crm</title>
</head>

<body class="adminpage">

    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
            <div id="nav-header" class="d-flex align-items-center p-3">
                <img src="./assets/image/logo.png" alt="Logo">
                <h4>..Sales</h4>
            </div>
            <!-- Menu Items -->
            <div id="nav-content">
                <div class="nav-button">
                    <i class="fas fa-tachometer-alt"></i><a href="./sales.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                </div>
                <div class="nav-button">
                    <i class="fa-solid fa-pencil"></i>
                    <span class="ms-2">Leads</span>
                    <div class="submenu">
                        <a href="./add_leads.php" class="no-underline">
                            <div>Add Leads</div>
                        </a>
                        <a href="./updatelead.php" class="no-underline">
                            <div>Update leads</div>
                        </a>
                    </div>
                </div>
                <div class="nav-button">
                    <i class="fa-brands fa-android"></i>
                    <span class="ms-2">Product</span>
                    <div class="submenu">
                        <a href="./add_product.php" class="no-underline">
                            <div>add Product</div>
                        </a>
                        <a href="./order_details.php" class="no-underline">
                            <div>order details</div>
                        </a>
                    </div>
                </div>
                <div class="nav-button">
                    <a href="./opprtunity.php" class="nav-link d-flex align-items-center">
                        <i class="fa-brands fa-codepen"></i>
                        <span class="ms-2">Opportunities</span>
                    </a>
                </div>

                <div class="nav-button">
                    <a href="./sales_tasks.php" class="nav-link d-flex align-items-center">
                        <i class="fa-solid fa-list-check"></i>
                        <span class="ms-2">tasks</span>
                    </a>
                </div>
                <a href="./customer_data.php" class="nav-button text-decoration-none">
                    <i class="fa-solid fa-database"></i>
                    <span class="ms-2">customer data</span>
                </a>
            </div>

            <!-- Footer Section -->
            <div id="nav-footer" class="p-3">
                <img src="./assets/image/user.png" alt="User">
                <h6><?= htmlspecialchars($user_name); ?></h6>
                <p>Sales</p>
            </div>
            <div class="nav-button">
                <a href="lib/logout.php" class="no-underline">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
    </div>



    <!-- Right Side: Add Lead Form -->
    <div class="col-md-9 col-lg-10 p-4 right">
        <h2 class="mb-4">Add Lead</h2>
        <form action="" method="POST">
            <!-- <div class="mb-3">
                <label for="leadID" class="form-label">Lead ID</label>
                <input type="text" class="form-control" id="leadID" name="leadID" required>
            </div> -->
            <div class="mb-3">
                <label for="source" class="form-label">Source</label>
                <input type="text" class="form-control" id="source" name="source" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" class="form-control" id="status" name="status" required>
            </div>
            <div class="mb-3">
                <label for="assignedTo" class="form-label">Assigned To</label>
                <input type="text" class="form-control" id="assignedTo" name="assignedTo" required>
            </div>
            <div class="mb-3">
                <label for="createdDate" class="form-label">Created Date</label>
                <input type="date" class="form-control" id="createdDate" name="createdDate" required>
            </div>
            <div class="mb-3">
                <label for="customerID" class="form-label">Customer ID</label>
                <input type="text" class="form-control" id="customerID" name="customerID" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>