<?php
session_start();
require_once 'lib/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$db = new database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fromName = htmlspecialchars($_POST['fromName'] ?? "");
    $billTo = htmlspecialchars($_POST['billTo'] ?? "");
    $shipTo = htmlspecialchars($_POST['shipTo'] ?? "");
    $amountPaid = floatval($_POST['amountPaid'] ?? 0);
    $dueAmount = floatval($_POST['dueAmount'] ?? 0);
    $invoiceDate = htmlspecialchars($_POST['invoiceDate'] ?? "");
    $paymentTerm = htmlspecialchars($_POST['paymentTerm'] ?? "");
    $dueDate = htmlspecialchars($_POST['dueDate'] ?? "");
    $poNumber = htmlspecialchars($_POST['poNumber'] ?? "");
    $notes = htmlspecialchars($_POST['notes'] ?? "");
    $totalAmount = floatval($_POST['total_amount'] ?? 0);
    $tax = floatval($_POST['tax'] ?? 0);
    $company_name = htmlspecialchars($_POST['company_name'] ?? "");
    $company_address = htmlspecialchars($_POST['company_address'] ?? "");
    $phone = htmlspecialchars($_POST['phone'] ?? "");

    $items = $_POST['items'] ?? "[]";
    $decodedItems = json_decode($items, true);

    if (!is_array($decodedItems)) {
        echo json_encode(['success' => false, 'message' => 'Error: Invalid items data']);
        exit();
    }

    try {
        $db->pdo->beginTransaction();
        $query = "INSERT INTO invoices (from_name, bill_to, ship_to, amount_paid, due_amount, invoice_date, payment_term, due_date, po_number, notes, tax, total_amount, company_name, company_address, phone) 
                  VALUES (:fromName, :billTo, :shipTo, :amountPaid, :dueAmount, :invoiceDate, :paymentTerm, :dueDate, :poNumber, :notes, :tax, :total_amount, :company_name, :company_address, :phone)";

        $stmt = $db->pdo->prepare($query);
        $stmt->execute([
            ':fromName' => $fromName,
            ':billTo' => $billTo,
            ':shipTo' => $shipTo,
            ':amountPaid' => $amountPaid,
            ':dueAmount' => $dueAmount,
            ':invoiceDate' => $invoiceDate,
            ':paymentTerm' => $paymentTerm,
            ':dueDate' => $dueDate,
            ':poNumber' => $poNumber,
            ':notes' => $notes,
            ':tax' => $tax,
            ':company_name' => $company_name,
            ':company_address' => $company_address,
            ':phone' => $phone,
            ':total_amount' => $totalAmount
        ]);

        $invoiceId = $db->pdo->lastInsertId();
        if ($invoiceId) {
            $itemQuery = "INSERT INTO invoice_items (invoice_id, description, quantity, rate, tax, amount) 
                          VALUES (:invoiceId, :description, :quantity, :rate, :tax, :amount)";
            $itemStmt = $db->pdo->prepare($itemQuery);

            foreach ($decodedItems as $item) {
                $itemStmt->execute([
                    ':invoiceId'   => $invoiceId,
                    ':description' => htmlspecialchars($item['description']),
                    ':quantity'    => intval($item['quantity']),
                    ':rate'        => floatval($item['rate']),
                    ':tax'         => floatval($item['tax']),
                    ':amount'      => floatval($item['amount'])
                ]);
            }
        }
        $db->pdo->commit();

        echo "<script>
                alert('Invoice generated successfully!');
                window.location.href = 'invoice.php';
              </script>";
    } catch (PDOException $e) {
        $db->pdo->rollBack();
        echo "<script>
                alert('Error generating invoice: " . addslashes($e->getMessage()) . "');
              </script>";
    }

    exit();
}
?>

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


    <div class="container py-4">
        <h2 class="text-center mb-4">Invoice Generator</h2>
        <form id="invoiceForm" method="post">
            <div class="row mb-3">
                <h1>APCORN INNOVATION</h1>
            </div>

            <!-- Form fields for invoice -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="fromName" class="form-label">From</label>
                    <input type="text" class="form-control" id="fromName" name="fromName" required>
                </div>
                <div class="col-md-4">
                    <label for="billTo" class="form-label">Bill To</label>
                    <input type="text" class="form-control" id="billTo" name="billTo" required>
                </div>
                <div class="col-md-4">
                    <label for="company_name" class="form-label">Company</label>
                    <input type="text" class="form-control" id="company_name" name="company_name">
                </div>
                <div class="col-md-6">
                    <label for="company_address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="company_address" name="company_address" required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="col-md-4">
                    <label for="shipTo" class="form-label">Ship To</label>
                    <input type="text" class="form-control" id="shipTo" name="shipTo">
                </div>


            </div>

            <!-- Items Table -->
            <h4>Items</h4>
            <table class="table" id="itemsTable">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Tax (%)</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button type="button" class="btn btn-primary" onclick="addItem()">+ Add Item</button>

            <!-- Additional Invoice Information -->
            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="amountPaid" class="form-label">Amount Paid</label>
                    <input type="number" class="form-control" id="amountPaid" name="amountPaid" oninput="updateDueAmount()">
                </div>
                <div class="col-md-4">
                    <label for="dueAmount" class="form-label">Due Amount</label>
                    <input type="number" class="form-control" id="dueAmount" name="dueAmount" readonly>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <label for="invoiceDate" class="form-label">Invoice Date</label>
                    <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" required>
                </div>
                <div class="col-md-4">
                    <label for="paymentTerm" class="form-label">Payment Term</label>
                    <input type="text" class="form-control" id="paymentTerm" name="paymentTerm" required>
                </div>
                <div class="col-md-4">
                    <label for="dueDate" class="form-label">Due Date</label>
                    <input type="date" class="form-control" id="dueDate" name="dueDate" required>
                </div>
                <div class="col-md-4 mt-3">
                    <label for="poNumber" class="form-label">PO Number</label>
                    <input type="text" class="form-control" id="poNumber" name="poNumber">
                </div>
            </div>

            <!-- Notes Section -->
            <div class="mt-3">
                <label for="notes" class="form-label">Notes and Terms</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>

            <!-- Summary Section -->
            <div class="text-end mt-3">
                <p>Tax: BDT <span id="tax">0</span></p>
                <p>Total: BDT <span id="total">0</span></p>
                <p>Amount Paid: BDT <span id="amountPaidText">0.00</span></p>
                <p>Due Amount: BDT <span id="balanceDue">0</span></p>
            </div>

            <input type="hidden" id="items" name="items">
            <button type="submit" class="btn btn-primary mt-3">Generate Invoice</button>
            <button type="button" class="btn btn-secondary mt-3" onclick="previewInvoice()">Preview Invoice</button>
        </form>

        <!-- Modal for Preview -->
        <div class="modal" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="invoicePreviewModalLabel">Invoice Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="previewContent">
                        <!-- Invoice Preview Content will be injected here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="printInvoice()">Print Invoice</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popupModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="popupMessage"></p> <!-- Dynamic message content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to add a new item row to the table
        function addItem() {
            let tableBody = document.querySelector('#itemsTable tbody');
            let row = document.createElement('tr');
            row.innerHTML = `
            <td><input type="text" class="form-control item-description" name="description[]"></td>
            <td><input type="number" class="form-control item-quantity" name="quantity[]" min="1" oninput="updateAmount(this)"></td>
            <td><input type="number" class="form-control item-rate" name="rate[]" min="0" oninput="updateAmount(this)"></td>
            <td><input type="number" class="form-control item-tax" name="tax[]" min="0" oninput="updateAmount(this)"></td>
            <td><input type="number" class="form-control item-amount" name="amount[]" readonly></td>
            <td><button type="button" class="btn btn-danger" onclick="removeItem(this)">X</button></td>
        `;
            tableBody.appendChild(row);
        }

        // Function to remove an item row
        function removeItem(button) {
            let row = button.closest('tr');
            row.remove();
            updateSummary();
        }

        // Function to update item amount and calculate the total
        function updateAmount(input) {
            let row = input.closest('tr');
            let quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            let rate = parseFloat(row.querySelector('.item-rate').value) || 0;
            let tax = parseFloat(row.querySelector('.item-tax').value) || 0;

            let amount = (quantity * rate) + (quantity * rate * tax / 100);
            row.querySelector('.item-amount').value = amount.toFixed(2);

            updateSummary();
        }

        // Function to update the summary details (tax, total, due)
        function updateSummary() {
            let total = 0;
            document.querySelectorAll('.item-amount').forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            let tax = (total * 0.15).toFixed(2); // Assuming 15% tax
            document.getElementById('tax').textContent = tax;
            document.getElementById('total').textContent = total.toFixed(2);

            updateDueAmount();
        }

        // Function to update due amount when amount paid changes
        function updateDueAmount() {
            let totalAmount = parseFloat(document.getElementById('total').textContent) || 0;
            let amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
            let dueAmount = totalAmount - amountPaid;

            document.getElementById('dueAmount').value = dueAmount.toFixed(2);
            document.getElementById('balanceDue').textContent = dueAmount.toFixed(2);
        }

        // Function to collect items and encode them as JSON
        function collectItems() {
            let items = [];
            document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
                items.push({
                    description: row.querySelector('.item-description').value,
                    quantity: row.querySelector('.item-quantity').value,
                    rate: row.querySelector('.item-rate').value,
                    tax: row.querySelector('.item-tax').value,
                    amount: row.querySelector('.item-amount').value
                });
            });
            return JSON.stringify(items);
        }

        // Function to handle form submission
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('items').value = collectItems();
            this.submit();
        });

        // Function to preview the invoice
        function previewInvoice() {
            let previewContent = document.getElementById('previewContent');
            previewContent.innerHTML = '';

            // Collect Invoice Data
            let data = {
                invoiceDate: document.getElementById('invoiceDate')?.value || 'N/A',
                invoiceNumber: document.getElementById('poNumber')?.value || 'N/A',
                clientName: document.getElementById('billTo')?.value || 'N/A',
                shipTo: document.getElementById('shipTo')?.value || '',
                clientCompany: document.getElementById('company_name')?.value || '',
                clientAddress: document.getElementById('company_address')?.value || 'N/A',
                clientPhone: document.getElementById('phone')?.value || 'N/A',
                subtotal: parseFloat(document.getElementById('total')?.textContent) || 0.00,
                tax: parseFloat(document.getElementById('tax')?.textContent) || 0.00,
                total: parseFloat(document.getElementById('total')?.textContent) || 0.00,
                paid: parseFloat(document.getElementById('amountPaid')?.value) || 0.00,
                due: parseFloat(document.getElementById('dueAmount')?.value) || 0.00,
                items: JSON.parse(collectItems()) || [],
            };

            // Debugging: Log collected data
            console.log("Invoice Data:", data);

            // Ensure items exist
            let itemRows = data.items.length ?
                data.items
                .map(
                    item => `
            <tr>
                <td>${item.quantity}</td>
                <td>${item.description}</td>
                <td>BDT ${parseFloat(item.rate).toFixed(2)}</td>
                <td>BDT ${parseFloat(item.amount).toFixed(2)}</td>
            </tr>
        `
                )
                .join("") :
                "<tr><td colspan='4' class='text-center'>No items added.</td></tr>";

            // Generate Invoice Preview
            previewContent.innerHTML = `
        <div id="invoiceToPrint" class="invoice-wrapper">
            <div class="invoice-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="invoice-company-name">APCORN INNOVATION</h1>
                    <p class="invoice-address">
                        Address: Eastern View (12th Floor), 50, Nayapaltan, Dhaka-1000<br>
                        Phone: +8801581-195132<br>
                        Email: accounts@apcorn.com<br>
                        Web: <a href="https://apcorn.com" target="_blank">apcorn.com</a>
                    </p>
                </div>
                <div>
                    <h1 class="invoice-title">INVOICE</h1>
                </div>
            </div>

            <div class="invoice-info row mb-4">
                <div class="col-12 col-md-6">
                    <h2 class="invoice-date">Date: ${data.invoiceDate}</h2>
                    <h2 class="invoice-number">INVOICE # ${data.invoiceNumber}</h2>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <p><strong>To:</strong> ${data.clientName}</p>
                    <p>${data.clientCompany}</p>
                    <p>${data.clientAddress}</p>
                    <p>${data.clientPhone}</p>
                </div>
            </div>

            <table class="table table-bordered invoice-table">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Description</th>
                        <th>Unit Price</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemRows}
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <table class="table invoice-summary">
                        <tr><th>Subtotal:</th><td>BDT ${data.subtotal.toFixed(2)}</td></tr>
                        <tr><th>Sales Tax:</th><td>BDT ${data.tax.toFixed(2)}</td></tr>
                        <tr><th>Total:</th><td>BDT ${data.total.toFixed(2)}</td></tr>
                        <tr><th>Paid:</th><td>BDT ${data.paid.toFixed(2)}</td></tr>
                        <tr><th>Due:</th><td>BDT ${data.due.toFixed(2)}</td></tr>
                    </table>
                </div>
            </div>

            <p class="text-center"><strong>Thank you for using our software</strong></p>
        </div>
    `;
            const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
            modal.show();
        }


        // Function to print the invoice
        function printInvoice() {
            let printContent = document.getElementById('previewContent').innerHTML;
            let newWindow = window.open('', '', 'height=800,width=1200');
            newWindow.document.write(`<html><head><title>Invoice</title></head><body>${printContent}</body></html>`);
            newWindow.document.close();
            newWindow.print();
        }
    </script>

    <!-- Include Bootstrap JS (make sure to include the correct version) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/nav.js"></script>
</body>

</html>