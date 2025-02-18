<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$db = new database();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity
        ];
    }

    header("Location: copro.php");
    exit();
}


if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['remove_product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']--;
        if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

$query = "SELECT ProductID, Name, Category, Price, StockLevel FROM product";
$result = $db->select($query);
$products = $result ? $result : [];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Apicorn CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        form {
            padding: 7px;
        }
    </style>
</head>

<body class="adminpage">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4> &nbsp;cous <br> &nbsp;tomer</h4>
                </div>
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./customer.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-brands fa-android"></i>
                        <span class="ms-2">Product</span>
                        <div class="submenu">
                            <a href="./copro.php" class="no-underline">
                                <div>All Product</div>
                            </a>
                            <a href="./coustomer_order.php" class="no-underline">
                                <div>Order Details</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./message.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Messages &</span>&nbsp;
                            <i class="fa-solid fa-headset"></i>
                            <span class="ms-2">Support</span>
                        </a>
                    </div>
                </div>

                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6>Setuuuuuuuu</h6>
                    <p>sales</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="col-md-9 col-lg-10 right">
                <div class="d-flex justify-content-end p-3 ">

                    <button class="btn btn-light cart-icon" data-bs-toggle="modal" data-bs-target="#cartModal">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <span class="badge bg-primary"><?= count($_SESSION['cart'] ?? []) ?></span>
                    </button>
                </div>

                <div class="product-card-container row row-cols-1 row-cols-md-3 g-4 ">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="./assets/image/logo.png" alt="Product Image" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product['Name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($product['Category']) ?></p>
                                    <p class="card-price">৳<?= htmlspecialchars($product['Price']) ?></p>
                                    <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?= $product['ProductID'] ?>">
                                        <input type="hidden" name="product_name" value="<?= $product['Name'] ?>">
                                        <input type="hidden" name="product_price" value="<?= $product['Price'] ?>">
                                        <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Cart Modal -->
            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($_SESSION['cart'])) { ?>
                                <ul class="list-group">
                                    <?php foreach ($_SESSION['cart'] as $product_id => $product) { ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <!-- Product Name and Quantity -->
                                            <span class="cart-item-name"><?= htmlspecialchars($product['name']) ?> x <?= $product['quantity'] ?></span>

                                            <!-- Product Price -->
                                            <span class="cart-item-price">৳<?= $product['price'] * $product['quantity'] ?></span>

                                            <!-- Remove Button -->
                                            <form method="POST" class="d-inline remove-item-form">
                                                <input type="hidden" name="remove_product_id" value="<?= $product_id ?>">
                                                <button type="submit" name="remove_from_cart" class="btn btn-danger btn-sm">Remove</button>
                                            </form>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <div class="total-container mt-3">
                                    <p><strong>Total: ৳<?= array_sum(array_map(fn($p) => $p['price'] * $p['quantity'], $_SESSION['cart'])) ?></strong></p>
                                </div>
                                <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                            <?php } else { ?>
                                <p>Your cart is empty.</p>
                            <?php } ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            <script src="./assets/js/customerdata.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
            <script src="./assets/js/nav.js"></script>
        </div>
    </div>
</body>

</html>