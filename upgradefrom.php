<?php
session_start();
require_once 'lib/database.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: index.php");
    exit();
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
                <h4>Admin Panel</h4>
            </div>
            <!-- Menu Items -->
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
            
            <!-- Footer Section -->
            <div id="nav-footer" class="p-3">
                <img src="./assets/image/user.png" alt="User">
                <h6>John Doe</h6>
                <p>Admin</p>
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
            <div class="card shadow-lg rounded-3">
                <div class="card-body">
                    <h2 class="mb-4 text-center text-primary">All Customers</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered shadow-sm rounded-3">
                            <thead class="table-dark">
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
                            <tbody>
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
                                            <a class="btn btn-warning btn-sm shadow-sm" href="lib/editCustomer.php?CustomerID=<?php echo $row['CustomerID']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Customer">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm shadow-sm" href="lib/deleteCustomer.php?id=<?php echo $row['CustomerID'] ?>" onclick="return confirm('Are you sure to delete?');" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Customer">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php endif; ?>

<?php
    $query = "SELECT * FROM user";
    $read = $db->select($query);

    if ($read):
?>
        <div class="col-md-9 col-lg-10 p-4 right">
            <div class="card shadow-lg rounded-3">
                <div class="card-body">
                    <h2 class="mb-4 text-center text-success">All Employees</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered shadow-sm rounded-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>UserID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $read->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo $row['UserID'] ?></td>
                                        <td><?php echo $row['Name'] ?></td>
                                        <td><?php echo $row['Email'] ?></td>
                                        <td><?php echo $row['role'] ?></td>
                                        <td><?php echo $row['Department'] ?></td>
                                        <td>
                                            <a class="btn btn-warning btn-sm shadow-sm" href="lib/editEmployee.php?UserID=<?php echo $row['UserID']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Employee">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm shadow-sm" href="lib/deleteEmployee.php?id=<?php echo $row['UserID'] ?>" onclick="return confirm('Are you sure to delete?');" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Employee">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php endif; ?>

<!-- Success/Error Alerts -->
<?php if(isset($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> <?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif(isset($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<script src="./assets/js/all_user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

</body>

</html>