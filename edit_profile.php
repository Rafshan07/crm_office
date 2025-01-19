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
    
            <div class="container py-5 edit_profile">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header text-center bg-primary text-white">
                                <h3>Edit Profile</h3>
                            </div>
                            <div class="card-body">
                                <form id="editProfileForm">
                                    <!-- User ID -->
                                    <div class="mb-3">
                                        <label for="editUserId" class="form-label">User ID</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="editUserId" 
                                            placeholder="Enter User ID" 
                                            disabled>
                                    </div>
            
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="editName" class="form-label">Name</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="editName" 
                                            placeholder="Enter Name">
                                    </div>
            
                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="editPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input 
                                                type="password" 
                                                class="form-control" 
                                                id="editPassword" 
                                                placeholder="Enter New Password">
                                            <button 
                                                class="btn btn-outline-secondary toggle-password" 
                                                type="button">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">
                                            Use a strong password with at least 8 characters, including letters and numbers.
                                        </small>
                                    </div>
            
                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button 
                                            type="submit" 
                                            class="btn btn-primary btn-lg">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            



      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script> 
    <script src="./assets/js/nav.js"></script>  
</body>
</html>
