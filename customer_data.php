<?php
ob_start();
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {

    header("Location: index.php");
    exit();
}
$db = new database();

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];

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
    $query = "SELECT * FROM customer";
    $read = $db->select($query);

    if ($read):

    ?>

        <div class="col-md-9 col-lg-10 p-4 right">
            <h3>Customer Data</h3>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Company</th>
                        <th>Industry</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="customer-data-table">

                    <?php while ($row = $read->fetch(PDO::FETCH_ASSOC)): ?>

                        <tr>
                            <td><?php echo $row['CustomerID'] ?></td>
                            <td><?php echo $row['Name'] ?></td>
                            <td><?php echo $row['Email'] ?></td>
                            <td><?php echo $row['Phone'] ?></td>
                            <td><?php echo $row['Address'] ?></td>
                            <td><?php echo $row['Company'] ?></td>
                            <td><?php echo $row['Industry'] ?></td>
                            <td>
                                <a href="lib/show_tasks.php?customerid=<?php echo $row['CustomerID']; ?>" class="btn btn-success">View Tasks</a>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Task ID:</strong> <span id="task-id">Loading...</span></p>
                    <p><strong>Due Date:</strong> <span id="due-date">Loading...</span></p>
                    <p><strong>Assigned To:</strong> <span id="assigned-to">Loading...</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/customerdata.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

</body>

</html>