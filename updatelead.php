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

    <div class="container-fluid">
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
    <?php
    $db = new database();
    $query = "SELECT * FROM lead";
    $read = $db->select($query);
    ?>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>

    <div class="col-md-9 right">
        <h3 class="mt-3">Leads Table</h3>
        <?php
        if (isset($_GET['success'])) {
            $successMessage = $_GET['success'];
            echo "<div class='alert alert-success'>$successMessage</div>";
        }
        ?>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Lead ID</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created Date</th>
                    <th>Customer ID</th>
                    <th>Actions</th>
                </tr>
                <?php if ($read) { ?>

                    <?php
                    $i = 1;
                    while ($row = $read->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $row['LeadID'] ?></td>
                            <td><?php echo $row['Source'] ?></td>
                            <td><?php echo $row['Status'] ?></td>
                            <td><?php echo $row['AssignedTo'] ?></td>
                            <td><?php echo $row['CreateDate'] ?></td>
                            <td><?php echo $row['CustomerID'] ?></td>
                            <td>
                                <a href="lib/editLead.php?id=<?php echo $row['LeadID'] ?>" class="btn btn-warning">Edit</a>
                                <a href="lib/deleteLead.php?id=<?php echo $row['LeadID'] ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>

                    <?php } ?>
                <?php } else { ?>
                    <p>No Data Found!</p>
                <?php } ?>
            </thead>

        </table>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <div class="mb-3">
                            <label for="lead-id" class="form-label">Lead ID</label>
                            <input type="text" class="form-control" id="lead-id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="lead-source" class="form-label">Source</label>
                            <input type="text" class="form-control" id="lead-source" required>
                        </div>
                        <div class="mb-3">
                            <label for="lead-status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="lead-status" required>
                        </div>
                        <div class="mb-3">
                            <label for="lead-assigned-to" class="form-label">Assigned To</label>
                            <input type="text" class="form-control" id="lead-assigned-to" required>
                        </div>
                        <div class="mb-3">
                            <label for="lead-created-date" class="form-label">Created Date</label>
                            <input type="date" class="form-control" id="lead-created-date" required>
                        </div>
                        <div class="mb-3">
                            <label for="lead-customer-id" class="form-label">Customer ID</label>
                            <input type="text" class="form-control" id="lead-customer-id" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/updateleads.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>