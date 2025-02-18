<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {

    header("Location: index.php");
    exit();
}
$db = new database();
$customer_name = "Guest"; // Default value

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch customer name from customer table
    $query = "SELECT Name FROM customer WHERE CustomerID = ?";
    $customer = $db->select($query, [$user_id]);

    $customer_name = $customer ? $customer[0]['Name'] : "Unknown Customer";
}

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
</head>

<body class="adminpage">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4> &nbsp;cous <br> &nbsp;tomer</h4>
                </div>
                <!-- Menu Items -->
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./customer.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-brands fa-android"></i>
                        <span class="ms-2">Product</span>
                        <div class="submenu">
                            <a href="./copro.php" class="no-underline">
                                <div>all Product</div>
                            </a>
                            <a href="./coustomer_order.php" class="no-underline">
                                <div>order details</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./message.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Message and</span>&nbsp;
                            <i class="fa-solid fa-headset"></i>
                            <span class="ms-2">support</span>
                        </a>
                    </div>
                </div>

                <!-- Footer Section -->
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
        </div>
    </div>

    <!-- Popup Buttons Section -->
    <div class="col-md-9 col-lg-10">
        <div class="d-flex justify-content-end p-3">
            <div class="popup-wrapper">
                <button class="popup-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge">2</span>
                </button>
                <div class="popup-content">
                    <div class="popup-header">Cart</div>
                    <ul class="popup-list">
                        <li>
                            <a href="#"><i class="fas fa-box"></i> Product 1</a>
                            <span>$20</span>
                        </li>
                        <li>
                            <a href="#"><i class="fas fa-box"></i> Product 2</a>
                            <span>$35</span>
                        </li>
                    </ul>
                    <div class="popup-footer">
                        <a href="./cart.php" class="btn btn-primary w-100 mt-2">View Cart</a>
                    </div>
                </div>
            </div>
            <div class="popup-wrapper">
                <button class="popup-btn">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                <div class="popup-content">
                    <div class="popup-header">Notifications</div>
                    <ul class="popup-list">
                        <li><a href="#"><i class="fas fa-user-plus"></i> New lead added</a></li>
                        <li><a href="#"><i class="fas fa-box"></i> Product updated</a></li>
                    </ul>
                </div>
            </div>

            <div class="popup-wrapper">
                <button class="popup-btn">
                    <i class="fas fa-comment-dots"></i>
                    <span class="badge">5</span>
                </button>
                <div class="popup-content">
                    <div class="popup-header">Messages</div>
                    <ul class="popup-list">
                        <li><a href="#"><i class="fas fa-envelope"></i> John Doe: Hello!</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> Jane Smith: Meeting?</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> Alex: Updates?</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

    <style>
        .popup-wrapper {
            position: relative;
            display: inline-block;
            margin: 0 10px;
        }

        .popup-btn {
            background: linear-gradient(135deg, #007bff, #4f9cfd);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            cursor: pointer;
            position: relative;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease-in-out;
        }

        .popup-btn:hover {
            background: linear-gradient(135deg, #0056b3, #3b8df5);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .popup-btn i {
            font-size: 20px;
        }

        .popup-btn .badge {
            background: red;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
            padding: 4px 8px;
            position: absolute;
            top: -5px;
            right: -5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Popup Container */
        .popup-content {
            position: absolute;
            top: 110%;
            right: 0;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 15px;
            min-width: 300px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease-in-out;
            z-index: 1000;
        }

        .popup-wrapper:hover .popup-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .popup-header {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
        }

        .popup-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .popup-list li {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .popup-list li:hover {
            background: #f9fbfd;
            cursor: pointer;
        }

        .popup-list li a {
            text-decoration: none;
            color: #007bff;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .popup-list li a i {
            font-size: 18px;
            color: #007bff;
        }

        .popup-list li:last-child {
            border-bottom: none;
        }


        .popup-footer {
            text-align: center;
            margin-top: 10px;
        }

        .popup-footer .btn {
            background: linear-gradient(135deg, #007bff, #4f9cfd);
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .popup-footer .btn:hover {
            background: linear-gradient(135deg, #0056b3, #3b8df5);
            transform: translateY(-2px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</body>

</html>