<?php
require 'database.php';

if (isset($_GET['id'])) {
    $db = new database();
    $customerID = $_GET['id'];

    $query = "DELETE FROM customer WHERE CustomerID=$customerID";
    if ($db->delete($query)) {
        echo "User deleted successfully!";
    } else {
        echo "Deletion failed!";
    }
    header("Location: ../upgradefrom.php");
}
