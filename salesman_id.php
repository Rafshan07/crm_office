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

                    <a href="./invoice.php" class="nav-button text-decoration-none">
                        <i class="fa-solid fa-file-invoice"></i>
                        <span>Invoice</span>
                    </a>
                    <a href="./edit_profile.php" class="nav-button text-decoration-none">
                        <i class="fa-solid fa-user"></i>
                        <span>Edit Profile</span>
                    </a>
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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $db->pdo->quote($_POST['name']);
        $email = $db->pdo->quote($_POST['email']);
        $role = $db->pdo->quote($_POST['role']);
        $department = $db->pdo->quote($_POST['department']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['role']) || empty($_POST['department']) || empty($_POST['password'])) {
            echo "All fields are required!";
        } else {
            $query = "INSERT INTO User (Name, Email, role, Department, Password) 
                  VALUES ($name, $email, $role, $department, '$password')";
            $create = $db->insert($query);

            if ($create) {
                header("Location: upgradefrom.php");
                exit;
            } else {
                echo "Error in registration!";
            }
        }
    }
    ?>


    <div class="col-md-9 col-lg-10 p-4 right">
        <div class="mb-3">
            <button class="btn btn-primary " onclick="location.href='_manage_users_create.php'">Create Customer Account</button><br>
        </div>
        <h3>Create Busnesses devoloper ID</h3>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="customer-form-group">
                <input type="text" class="form-control" id="name" name="name" placeholder=" " required>
                <label for="name">Name</label>
            </div>

            <div class="customer-form-group">
                <input type="text" class="form-control" id="department" name="department" placeholder=" " required>
                <label for="department">Department</label>
            </div>

            <div class="customer-form-group">
                <select class="form-control" id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="sales">Sales</option>
                    <option value="staff">Staff</option>
                </select>
                <label for="role">Role</label>
            </div>

            <div class="customer-form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>

            <div class="customer-form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>