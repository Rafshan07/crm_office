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
            header("Location: add_product.php");
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
    $query = "SELECT * FROM product";
    $read = $db->select($query);
    ?>
    <?php
    if (isset($error)) {
        echo $error;
    }
    ?>
    <div class="container right">
        <div class="row">
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Add Product Button -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

                <!-- Product Table -->
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock Level</th>
                            <th>Actions</th>
                        </tr>
                    <tbody>
                        <?php if ($read) { ?>

                            <?php
                            $i = 1;
                            while ($row = $read->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo $row['ProductID'] ?></td>
                                    <td><?php echo $row['Name'] ?></td>
                                    <td><?php echo $row['Category'] ?></td>
                                    <td><?php echo $row['Price'] ?></td>
                                    <td><?php echo $row['StockLevel'] ?></td>
                                    <td>
                                        <a href="lib/editProduct.php?id=<?php echo $row['ProductID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="lib/deleteProduct.php?id=<?php echo $row['ProductID'] ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>

                            <?php } ?>
                        <?php } else { ?>
                            <p>No Data Found!</p>
                        <?php } ?>
                    </tbody>

                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="add_product.php">
                    <div class="modal-body">
                        <!-- <div class="mb-3">
                        <label for="productId" class="form-label">Product ID</label>
                        <input type="text" class="form-control" id="productId" required>
                    </div> -->
                        <!-- <div class="mb-3">
                        <label for="productPhoto" class="form-label">Upload Photo</label>
                        <input type="file" class="form-control" id="productPhoto" accept="image/*" required>
                        <img id="photoPreview" class="mt-2" style="display: none; width: 100px; height: 100px; object-fit: cover;">
                    </div> -->
                        <div class="mb-3">
                            <label for="productName" class="form-label">Name</label>
                            <input type="text" name="Name" class="form-control" id="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <input type="text" name="Category" class="form-control" id="productCategory" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" name="Price" class="form-control" id="productPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="stockLevel" class="form-label">Stock Level</label>
                            <input type="number" name="StockLevel" class="form-control" id="stockLevel" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <script src="./assets/js/addproduct.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>