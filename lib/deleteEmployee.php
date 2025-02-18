<?php
require 'database.php';

if (isset($_GET['id'])) {
    $db = new database();
    $userID = $_GET['id'];

    $query = "DELETE FROM user WHERE UserID=$userID";
    if ($db->delete($query)) {
        echo "User deleted successfully!";
    } else {
        echo "Deletion failed!";
    }
    header("Location: ../upgradefrom.php");
}