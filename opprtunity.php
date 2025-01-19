<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
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
                        <i class="fas fa-tachometer-alt"></i><a href="./main.php" class="no-underline"><span class="deshboard">Dashboard</span></a>
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
                    <h6>Setuuuuuuuu</h6>
                    <p>sales</p>
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




        <div class="col-md-9 col-lg-10 right" id="oppor-content">
            <!-- Create Button -->
            <div class="d-flex justify-content-between align-items-center my-3">
                <h3>Opportunities</h3>
                <button class="btn btn-primary" id="oppor-create-btn">Create Opportunity</button>
            </div>
        
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="oppor-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Opportunity ID</th>
                            <th>Title</th>
                            <th>Stage</th>
                            <th>Expected Revenue</th>
                            <th>Close Date</th>
                            <th>Probability (%)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="oppor-table-body">
                        <!-- Example Row -->
                        <tr>
                            <td>OPP001</td>
                            <td>Big Deal</td>
                            <td>Negotiation</td>
                            <td>5000</td>
                            <td>2025-01-15</td>
                            <td>80</td>
                            <td>
                                <button class="btn btn-sm btn-update" style="background-color: green; color: white;">
                                    <i class="fas fa-edit"></i> Update
                                </button>                                
                                <button class="btn btn-sm btn-delete" style="background-color: red; color: white;">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                                
                            </td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
        </div>
        
        <!-- Create/Edit Opportunity Modal -->
        <div class="modal fade" id="opporModal" tabindex="-1" aria-labelledby="opporModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="opporModalLabel">Create Opportunity</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="oppor-form">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="oppor-id" class="form-label">Opportunity ID</label>
                                <input type="text" class="form-control" id="oppor-id" required>
                            </div>
                            <div class="mb-3">
                                <label for="oppor-title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="oppor-title" required>
                            </div>
                            <div class="mb-3">
                                <label for="oppor-stage" class="form-label">Stage</label>
                                <input type="text" class="form-control" id="oppor-stage" required>
                            </div>
                            <div class="mb-3">
                                <label for="oppor-revenue" class="form-label">Expected Revenue</label>
                                <input type="number" class="form-control" id="oppor-revenue" required>
                            </div>
                            <div class="mb-3">
                                <label for="oppor-close-date" class="form-label">Close Date</label>
                                <input type="date" class="form-control" id="oppor-close-date" required>
                            </div>
                            <div class="mb-3">
                                <label for="oppor-probability" class="form-label">Probability (%)</label>
                                <input type="number" class="form-control" id="oppor-probability" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Opportunity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="./assets/js/opo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script> 
  <script src="./assets/js/nav.js"></script>
</body>
</html>
