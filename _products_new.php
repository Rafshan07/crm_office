<?php
session_start();
require_once 'lib/database.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: index.php");
    exit();
}

$db = new database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['Name']);
    $category = trim($_POST['Category']);
    $price = floatval($_POST['Price']);
    $stocklevel = intval($_POST['StockLevel']);

    if (empty($name) || empty($category) || empty($price) || empty($stocklevel)) {
        $error = "Field empty!";
    } else {
        $query = "INSERT INTO product (Name, Category, Price, StockLevel) 
                  VALUES (:Name, :Category, :Price, :StockLevel)";
        $stmt = $db->pdo->prepare($query);
        $stmt->bindParam(':Name', $name);
        $stmt->bindParam(':Category', $category);
        $stmt->bindParam(':Price', $price);
        $stmt->bindParam(':StockLevel', $stocklevel);

        if ($stmt->execute()) {
            header("Location: _products_new.php");
            exit();
        } else {
            echo "Error adding product!";
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


    <!-- Right-Side Our Products Section -->
    <?php
    $query = "SELECT * FROM product";
    $read = $db->select($query);
?>
<?php
    if (isset($error)) {
        echo "<div class='alert alert-danger' role='alert'>$error</div>";
    }
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Card for Product List -->
            <div class="card shadow-lg rounded-4">
                <div class="card-body">
                    <!-- Title -->
                    <h2 class="text-center text-success mb-4">Product List</h2>

                    <!-- Add Product Button -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus-circle"></i> Add Product
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover shadow-sm rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Product ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($read): ?>
                                    <?php while ($row = $read->fetch(PDO::FETCH_ASSOC)): ?>
                                        <tr>
                                            <td><?php echo $row['ProductID'] ?></td>
                                            <td><?php echo $row['Name'] ?></td>
                                            <td><?php echo $row['Category'] ?></td>
                                            <td><?php echo $row['Price'] ?></td>
                                            <td><?php echo $row['StockLevel'] ?></td>
                                            <td>
                                                <!-- Action Buttons with Icons -->
                                                <a href="lib/editProduct.php?id=<?php echo $row['ProductID'] ?>" class="btn btn-warning btn-sm shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Product">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="lib/deleteProduct.php?id=<?php echo $row['ProductID'] ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-sm shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Product">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No Data Found!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="fas fa-plus-circle"></i> Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="lib/addProduct.php" method="POST">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="stockLevel" class="form-label">Stock Level</label>
                            <input type="number" class="form-control" id="stockLevel" name="stockLevel" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>




    <script src="./assets/js/product.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>