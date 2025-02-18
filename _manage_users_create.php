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
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>apcorn crm</title>
</head>

<?php
$error = '';
$success = '';

$db = new database();

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $company = $_POST['company'];
    $industry = $_POST['industry'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($company) || empty($industry) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $query = "INSERT INTO customer (name, email, phone, address, company, industry, password) 
                  VALUES (:name, :email, :phone, :address, :company, :industry, :password)";

        $stmt = $db->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':industry', $industry);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            $success = "Customer account created successfully!";
            header("Location: upgradefrom.php");
            exit();
        } else {
            $error = "Error in registration!";
        }
    }
}
?>


<body class="adminpage">
    <?php if (isset($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    } ?>
    <?php if (isset($success)) {
        echo "<div class='alert alert-success'>$success</div>";
    } ?>

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

    <div class="col-md-9 col-lg-10 p-4 right">
        <div class="mb-3">
            <button class="btn btn-primary " onclick="location.href='staff_account.php'">Staff Create</button><br>
            <button class="btn btn-primary " onclick="location.href='salesman_id.php'">Busnesses devoloper Create</button>
        </div>
        <h3>Customer Account</h3>

        <form action="" method="POST" enctype="multipart/form-data">
            <!-- <div class="form-group customer-form-group">
                <input type="text" class="form-control" id="customer-id" name="customer_id" placeholder=" " required>
                <label for="customer-id">Customer ID</label>
            </div> -->
            <div class="form-group customer-form-group">
                <input type="text" class="form-control" id="name" name="name" placeholder=" " required>
                <label for="name">Full Name</label>
            </div>
            <div class="form-group customer-form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
            <div class="form-group customer-form-group">
                <input type="tel" class="form-control" id="phone" name="phone" placeholder=" " required>
                <label for="phone">Phone</label>
            </div>
            <div class="form-group customer-form-group">
                <textarea class="form-control" id="address" name="address" placeholder=" " rows="4" required></textarea>
                <label for="address">Address</label>
            </div>
            <div class="form-group customer-form-group">
                <input type="text" class="form-control" id="company" name="company" placeholder=" " required>
                <label for="company">Company</label>
            </div>
            <div class="form-group customer-form-group">
                <input type="text" class="form-control" id="industry" name="industry" placeholder=" " required>
                <label for="industry">Industry</label>
            </div>
            <div class="form-group customer-form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                <label for="password">Password</label>
            </div>
            <!-- Photo Upload Section -->
            <!-- <div class="form-group customer-form-group">
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                <label for="photo">Upload Photo</label>
            </div> -->
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>