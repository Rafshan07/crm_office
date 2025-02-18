<?php
require 'database.php';
$db = new Database();

$errorMessage = ""; // To store error messages to be displayed on the page

if (!isset($_GET['id'])) {
    die("No Opportunity ID provided!");
}

$opportunityID = $_GET['id'];

$stmt = $db->pdo->prepare("SELECT * FROM opportunity WHERE OpportunityID = ?");
$stmt->execute([$opportunityID]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) {
    die("Opportunity not found!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['Title'];
    $stage = $_POST['Stage'];
    $exprev = $_POST['ExpectedRevenue'];
    $close = $_POST['CloseDate'];
    $probability = $_POST['Probability'];
    $customerid = $_POST['CustomerID'];

    // Check if the CustomerID exists in the customer table
    $customerCheckStmt = $db->pdo->prepare("SELECT COUNT(*) FROM customer WHERE CustomerID = ?");
    $customerCheckStmt->execute([$customerid]);
    $customerExists = $customerCheckStmt->fetchColumn();

    if (!$customerExists) {
        $errorMessage = "Customer ID does not exist!";
    } else {
        $updateStmt = $db->pdo->prepare("UPDATE opportunity 
            SET Title=?, Stage=?, ExpectedRevenue=?, CloseDate=?, Probability=?, CustomerID=? 
            WHERE OpportunityID=?");

        if ($updateStmt->execute([$title, $stage, $exprev, $close, $probability, $customerid, $opportunityID])) {
            header("Location: ../opprtunity.php");
            exit();
        } else {
            $errorMessage = "Error updating opportunity!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Opportunity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center mb-4">Edit Opportunity</h2>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="Title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="Title" name="Title" value="<?= htmlspecialchars($lead['Title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="Stage" class="form-label">Stage</label>
                    <select class="form-select" id="Stage" name="Stage" required>
                        <option value="Prospecting" <?= $lead['Stage'] == 'Prospecting' ? 'selected' : '' ?>>Prospecting</option>
                        <option value="Proposal" <?= $lead['Stage'] == 'Proposal' ? 'selected' : '' ?>>Proposal</option>
                        <option value="Negotiation" <?= $lead['Stage'] == 'Negotiation' ? 'selected' : '' ?>>Negotiation</option>
                        <option value="Closed Won" <?= $lead['Stage'] == 'Closed Won' ? 'selected' : '' ?>>Closed Won</option>
                        <option value="Closed Lost" <?= $lead['Stage'] == 'Closed Lost' ? 'selected' : '' ?>>Closed Lost</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ExpectedRevenue" class="form-label">Expected Revenue ($)</label>
                    <input type="number" class="form-control" id="ExpectedRevenue" name="ExpectedRevenue" value="<?= htmlspecialchars($lead['ExpectedRevenue']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="CloseDate" class="form-label">Close Date</label>
                    <input type="date" class="form-control" id="CloseDate" name="CloseDate" value="<?= htmlspecialchars($lead['CloseDate']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="Probability" class="form-label">Probability (%)</label>
                    <input type="number" class="form-control" id="Probability" name="Probability" value="<?= htmlspecialchars($lead['Probability']) ?>" min="0" max="100" required>
                </div>

                <div class="mb-3">
                    <label for="CustomerID" class="form-label">Customer ID</label>
                    <input type="number" class="form-control" id="CustomerID" name="CustomerID" value="<?= htmlspecialchars($lead['CustomerID']) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Opportunity</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
