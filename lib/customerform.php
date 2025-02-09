<?php
require 'database.php';
require 'config.php';

$db = new database();
if (isset($_POST['submit'])) {
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $company = $_POST['company'] ?? null;
    $industry = $_POST['industry'] ?? null;


    if (!$name || !$email || !$phone || !$address || !$company || !$industry) {
        die("All fields are required.");
    }

    try {
        $query = $db->pdo->prepare(
            "INSERT INTO customer (name, email, phone, address, company, industry) 
             VALUES (:name, :email, :phone, :address, :company, :industry)"
        );
        $query->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':company' => $company,
            ':industry' => $industry,
        ]);
        echo "Redirecting...";
        header("Location: ../upgradefrom.html");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
