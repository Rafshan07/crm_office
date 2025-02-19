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

    <?php
    $db = new database();
    $query = "SELECT * FROM invoices";
    $read = $db->select($query);

    if ($read):
?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Card Container -->
                <div class="card shadow-sm border-light rounded-4">
                    <div class="card-body">
                        <!-- Title -->
                        <h2 class="text-center text-primary mb-4">All Invoices</h2>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>PO Number</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="invoice-tbody">
                                    <?php while ($row = $read->fetch(PDO::FETCH_ASSOC)): ?>
                                        <tr>
                                            <td><?php echo $row['id'] ?></td>
                                            <td><?php echo $row['po_number'] ?></td>
                                            <td><?php echo $row['invoice_date'] ?></td>
                                            <td>
                                                <!-- Action Buttons with Icons -->
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Invoice" onclick="showInvoiceModal(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Print Invoice" onclick="printInvoice()">
                                                    <i class="fas fa-print"></i> Print
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Invoice" onclick="deleteInvoice(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tooltip Initialization -->
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

<?php endif; ?>




    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Invoice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="invoiceToPrint">
                    <!-- Invoice content goes here (as in your original HTML) -->
                    <button class="btn btn-primary btn-sm" onclick="printInvoice()">Print</button> <!-- Print button -->
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

    <script>
        function showInvoiceModal(invoiceId) {
            // Send an AJAX request to fetch the invoice details
            fetch('get_invoice_details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        invoiceId: invoiceId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate modal with invoice details
                        document.getElementById('invoiceNumber').innerText = data.invoice.id;
                        document.getElementById('invoiceDate').innerText = data.invoice.invoice_date;
                        document.getElementById('invoiceToPrint').innerHTML = data.invoice.details;

                        // Show the modal
                        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
                    } else {
                        alert('Error fetching invoice details.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching invoice details.');
                });

            function printInvoice() {
                var invoiceContent = document.getElementById('invoiceToPrint').innerHTML; // Get the content inside the modal
                var originalContent = document.body.innerHTML; // Save the original content of the page

                document.body.innerHTML = invoiceContent; // Replace the page content with the invoice content
                window.print(); // Trigger the print dialog
                document.body.innerHTML = originalContent; // Restore the original content of the page
            }


        }


        function printInvoice() {
            var invoiceContent = document.getElementById('invoiceToPrint').innerHTML; // Get the content inside the modal
            var originalContent = document.body.innerHTML; // Save the original content of the page

            document.body.innerHTML = invoiceContent; // Replace the page content with the invoice content
            window.print(); // Trigger the print dialog
            document.body.innerHTML = originalContent; // Restore the original content of the page
        }

        function deleteInvoice(invoiceId) {
            if (confirm('Are you sure you want to delete this invoice?')) {
                // Send AJAX request to delete the invoice
                fetch('lib/delete_invoice.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            invoiceId: invoiceId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Invoice deleted successfully!');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Error deleting invoice.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred.');
                    });
            }
        }
    </script>
    <script src="./assets/js/all_user.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="./assets/js/nav.js"></script>

</body>

</html>