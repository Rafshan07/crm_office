<?php include 'include/database.php';
include 'include/config.php'; ?>

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

<body class="adminpage">

    < <div class="container-fluid">
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
                                <div>our Product</div>
                            </a>
                        </div>
                    </div>
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
                    <a href="./index.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>

            </div>
        </div>
        </div>
        <?php
        $db = new database();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
            $address = isset($_POST['address']) ? $_POST['address'] : null;
            $company = isset($_POST['company']) ? $_POST['company'] : null;
            $industry = isset($_POST['industry']) ? $_POST['industry'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;


            if (!$name || !$email || !$phone || !$address || !$company || !$industry || !$password) {
                die("All fields are required.");
            }


            try {
                $query = $db->pdo->prepare("INSERT INTO customer (name, email, phone, address, company, industry) VALUES (:name, :email, :phone, :address, :company, :industry)");
                $query->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':company' => $company,
                    ':industry' => $industry,
                ]);
                echo "Data inserted successfully!";
            } catch (PDOException $e) {
                die("Database error: " . $e->getMessage());
            }
        }

        ?>

        <div class="col-md-9 col-lg-10 p-4 right">
            <!-- Customer Form Section -->
            <h3>Create Account</h3>
            <form action="upgradefrom.php" method="POST" enctype="multipart/form-data">
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
                <!-- <div class="form-group customer-form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div> -->
                <!-- <div class="form-group customer-form-group">
                    <select class="form-control" id="role" name="role" required>
                        <option value="" disabled selected>Choose a Role</option>
                        <option value="sales">Sales</option>
                        <option value="customer">Customer</option>
                        <option value="support">Support</option>
                    </select>
                    <label for="role">Role</label>
                </div> -->
                <!-- Photo Upload Section -->
                <!-- <div class="form-group customer-form-group">
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                    <label for="photo">Upload Photo</label>
                </div> -->
                <button name="submit" type="submit" class="btn btn-primary mt-3" value="submit">Submit</button>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
        <script src="./assets/js/nav.js"></script>
</body>

</html>