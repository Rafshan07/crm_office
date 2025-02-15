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



  <div class="container mt-4 all-repot-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-primary">All Reports</h2>
      <button class="btn btn-success"><i class="fas fa-file-export"></i> Export Reports</button>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Report Name</th>
            <th>Date Created</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Sample Data -->
          <tr>
            <td>1</td>
            <td>Sales Analysis</td>
            <td>2025-01-01</td>
            <td><span class="badge bg-success">Completed</span></td>
            <td>
              <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
              <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Customer Engagement</td>
            <td>2025-01-05</td>
            <td><span class="badge bg-warning text-dark">Pending</span></td>
            <td>
              <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</button>
              <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
            </td>
          </tr>
          <!-- Add more rows dynamically -->
        </tbody>
      </table>
    </div>
  </div>

  <script src="./assets/js/reports.js"></script>
  <script src="/assets/js/all_user.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
  <script src="./assets/js/nav.js"></script>
</body>

</html>