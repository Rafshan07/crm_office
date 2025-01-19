<?php include 'include/database.php'?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
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



    <div class="col-md-9 col-lg-10 p-4 right">
        <h2 class="mb-4">All Users</h2>
        <table class="table table-striped table-hover alluser-table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Company</th>
                    <th>Industry</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="alluser-tbody">

            </tbody>
        </table>
    </div>


    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="alluser-edit-form">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editCustomerId" class="form-label">Customer ID</label>
                                <input type="text" class="form-control" id="editCustomerId" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="editName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editPhone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="editPhone" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="editAddress">
                            </div>
                            <div class="col-md-6">
                                <label for="editCompany" class="form-label">Company</label>
                                <input type="text" class="form-control" id="editCompany">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editIndustry" class="form-label">Industry</label>
                            <input type="text" class="form-control" id="editIndustry">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editRole" class="form-label">Role</label>
                                <select class="form-control" id="editRole">
                                    <option value="Sales">Sales</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Support">Support</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="editPassword">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveChanges">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>








    <script src="./assets/js/all_user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>