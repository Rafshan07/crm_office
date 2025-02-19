<?php
session_start();
require_once 'lib/database.php';

// Check if the user is a customer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize the database connection
    $db = new database();
    $pdo = $db->pdo; // Use the PDO connection from the database class

    // Get the customer ID from the session
    $customerID = $_SESSION['userid'];

    // Get the subject and description from the form
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    // Insert the ticket into the database
    try {
        $sql = "INSERT INTO tickets (CustomerID, Subject, Description, Status) 
                VALUES (:customerID, :subject, :description, 'open')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Set a success message and redirect
            $_SESSION['message'] = "Your ticket has been submitted successfully.";
            header("Location: submit_ticket.php");
            exit();
        } else {
            // Set an error message
            $_SESSION['message'] = "There was an error submitting your ticket.";
        }
    } catch (PDOException $e) {
        die("Error submitting ticket: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Ticket</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (exactly as provided) -->
            <div class="col-md-3 col-lg-2 p-0" id="nav-bar">
                <div id="nav-header" class="d-flex align-items-center p-3">
                    <img src="./assets/image/logo.png" alt="Logo">
                    <h4>Customer</h4>
                </div>
                <!-- Menu Items -->
                <div id="nav-content">
                    <div class="nav-button">
                        <i class="fas fa-tachometer-alt"></i><a href="./customer.php" class="no-underline"><span class="dashboard">Dashboard</span></a>
                    </div>
                    <div class="nav-button">
                        <i class="fa-brands fa-android"></i>
                        <span class="ms-2">Product</span>
                        <div class="submenu">
                            <a href="./copro.php" class="no-underline">
                                <div>All Products</div>
                            </a>
                            <a href="./customer_order.php" class="no-underline">
                                <div>Order Details</div>
                            </a>
                        </div>
                    </div>
                    <div class="nav-button">
                        <a href="./submit_ticket.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Ticket</span>
                        </a>
                    </div>
                    <div class="nav-button">
                        <a href="./message.php" class="nav-link d-flex align-items-center">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="ms-2">Message and</span>&nbsp;
                            <i class="fa-solid fa-headset"></i>
                            <span class="ms-2">Support</span>
                        </a>
                    </div>
                </div>

                <!-- Footer Section -->
                <div id="nav-footer" class="p-3">
                    <img src="./assets/image/user.png" alt="User">
                    <h6><?php echo $customer_name; ?></h6>
                    <p>Customer</p>
                </div>
                <div class="nav-button">
                    <a href="lib/logout.php" class="no-underline">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>


            <!-- Main Content -->
            <div class="col-md-9 col-lg-10" style="margin-left: 260px;">
                <div class="container mt-5">
                    <h2 class="text-center mb-4">Submit a Ticket</h2>

                    <!-- Success/Error message display -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info">
                            <?php echo $_SESSION['message']; ?>
                            <?php unset($_SESSION['message']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="bg-dark p-4 rounded shadow-sm">
                        <div class="mb-3">
                            <label for="subject" class="form-label text-white">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label text-white">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Ticket</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- Add Bootstrap 5 JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/updateleads.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>
