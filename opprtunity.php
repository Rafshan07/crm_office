<?php
ob_start();
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['Title']);
    $stage = trim($_POST['Stage']);
    $exprev = trim($_POST['ExpectedRevenue']);
    $close = trim($_POST['CloseDate']);
    $probability = trim($_POST['Probability']);
    $customerid = trim($_POST['CustomerID']);

    if (empty($title) || empty($stage) || empty($exprev) || empty($close) || empty($probability) || empty($customerid)) {
        $error = "All fields are required!";
    } elseif (!is_numeric($exprev) || !is_numeric($probability) || !is_numeric($customerid)) {
        $error = "Revenue, probability, and customer ID must be numbers.";
    } else {
        try {
            $checkQuery = "SELECT COUNT(*) FROM customer WHERE CustomerID = :customerid";
            $stmt = $db->pdo->prepare($checkQuery);
            $stmt->execute([':customerid' => $customerid]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $error = "Customer ID does not exist!";
            } else {
                $query = "INSERT INTO opportunity (Title, Stage, ExpectedRevenue, CloseDate, Probability, CustomerID) 
                          VALUES (:title, :stage, :exprev, :close, :probability, :customerid)";
                $stmt = $db->pdo->prepare($query);

                $create = $stmt->execute([
                    ':title' => $title,
                    ':stage' => $stage,
                    ':exprev' => $exprev,
                    ':close' => $close,
                    ':probability' => $probability,
                    ':customerid' => $customerid,
                ]);

                if ($create) {
                    $_SESSION['success'] = "Opportunity created successfully!";
                    header("Location: opprtunity.php");
                    exit();
                } else {
                    $error = "Error saving data!";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

$query = "SELECT * FROM opportunity";
$read = $db->select($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Opportunities</title>
</head>

<body>

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
                    <h6>Sales</h6>
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

    <div class="container mt-4">
        <h3 class="mb-4">Opportunities</h3>

        <?php if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>


        <?php if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        } ?>

        <?php
        if (isset($_GET['success'])) {
            $successMessage = $_GET['success'];
            echo "<div class='alert alert-success'>$successMessage</div>";
        }
        ?>

        <!-- Button to trigger the popup form -->
        <button class="btn btn-primary mb-3" id="addOpportunityBtn">Add Opportunity</button>

        <!-- Popup Form for adding an opportunity -->
        <div id="popupForm" class="popup-form" style="display: none;">
            <div class="popup-content">
                <h4>Create Opportunity</h4>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title:</label>
                        <input type="text" class="form-control" name="Title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stage:</label>
                        <input type="text" class="form-control" name="Stage">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expected Revenue:</label>
                        <input type="number" class="form-control" name="ExpectedRevenue">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Close Date:</label>
                        <input type="date" class="form-control" name="CloseDate">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Probability (%):</label>
                        <input type="number" class="form-control" name="Probability">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer ID:</label>
                        <input type="number" class="form-control" name="CustomerID">
                    </div>
                    <button type="submit" class="btn btn-success">Create Opportunity</button>
                    <button type="button" class="btn btn-secondary" id="closePopupBtn">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Table displaying opportunities -->
        <div class="container-fluid">
            <div class="row">
                <!-- Opportunity Table -->
                <div class="card shadow-lg rounded-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Opportunity ID</th>
                                        <th>Title</th>
                                        <th>Stage</th>
                                        <th>Expected Revenue</th>
                                        <th>Close Date</th>
                                        <th>Probability (%)</th>
                                        <th>Customer ID</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($read) {
                                        while ($row = $read->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <tr>
                                                <td><?php echo $row['OpportunityID']; ?></td>
                                                <td><?php echo $row['Title']; ?></td>
                                                <td><?php echo $row['Stage']; ?></td>
                                                <td>$<?php echo number_format($row['ExpectedRevenue'], 2); ?></td>
                                                <td><?php echo $row['CloseDate']; ?></td>
                                                <td><?php echo $row['Probability']; ?>%</td>
                                                <td><?php echo $row['CustomerID']; ?></td>
                                                <td>
                                                    <a href="lib/editOppurtinity.php?id=<?php echo $row['OpportunityID']; ?>" class="btn btn-warning btn-sm shadow-sm">Edit</a>
                                                    <a href="lib/deleteOppurtinity.php?id=<?php echo $row['OpportunityID']; ?>" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Are you sure to delete?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No Data Found!</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- CSS for Popup Form -->
    <style>
        .popup-form {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Dark background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>

    <!-- JavaScript to toggle the popup visibility -->
    <script>
        document.getElementById('addOpportunityBtn').addEventListener('click', function() {
            document.getElementById('popupForm').style.display = 'flex';
            this.style.display = 'none'; // Hide the "Add Opportunity" button
        });

        document.getElementById('closePopupBtn').addEventListener('click', function() {
            document.getElementById('popupForm').style.display = 'none';
            document.getElementById('addOpportunityBtn').style.display = 'inline-block'; // Show the "Add Opportunity" button again
        });
    </script>

    <script src="./assets/js/addtasks.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

</body>

</html>