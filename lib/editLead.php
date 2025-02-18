<?php
require 'database.php';
$db = new Database();

if (!isset($_GET['id'])) {
    die("No Lead ID provided!");
}

$leadID = $_GET['id'];

$stmt = $db->pdo->prepare("SELECT * FROM lead WHERE LeadID = ?");
$stmt->execute([$leadID]);
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) {
    die("Lead not found!");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source = $_POST['source'];
    $status = $_POST['status'];
    $assignedTo = $_POST['assignedTo'];
    $createDate = $_POST['createDate'];
    $customerID = $_POST['customerID'];

    $updateStmt = $db->pdo->prepare("UPDATE lead SET Source=?, Status=?, AssignedTo=?, CreateDate=?, CustomerID=? WHERE LeadID=?");
    if ($updateStmt->execute([$source, $status, $assignedTo, $createDate, $customerID, $leadID])) {
        header("Location: updatelead.php?success=Lead updated successfully!");
    } else {
        echo "Error updating lead!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="mb-4 text-center">Edit Lead</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Source</label>
                    <input type="text" name="source" class="form-control" value="<?= htmlspecialchars($lead['Source']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <input type="text" name="status" class="form-control" value="<?= htmlspecialchars($lead['Status']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Assigned To</label>
                    <input type="text" name="assignedTo" class="form-control" value="<?= htmlspecialchars($lead['AssignedTo']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Created Date</label>
                    <input type="date" name="createDate" class="form-control" value="<?= htmlspecialchars($lead['CreateDate']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Customer ID</label>
                    <input type="text" name="customerID" class="form-control" value="<?= htmlspecialchars($lead['CustomerID']) ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Lead</button>
                    <a href="sales.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>