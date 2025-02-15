<?php
require_once 'database.php';

class user
{
    private $pdo;

    public function __construct()
    {
        $db = new database();
        $this->pdo = $db->pdo;
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT UserID, Password, role FROM user WHERE email = :email LIMIT 1");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['Password'])) { 
                session_regenerate_id(true);
                $_SESSION['userid'] = $user['UserID'];
                $_SESSION['role'] = $user['role'];
                return $user['role'];
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        return false;
    }

    public function isCustomer($email, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT CustomerID, Password FROM customer WHERE email = :email LIMIT 1");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            return $customer && password_verify($password, $customer['Password']); 
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        return false;
    }

    public function getCustomerID($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT CustomerID FROM customer WHERE email = :email LIMIT 1");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            return $customer ? $customer['CustomerID'] : null;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        return null;
    }
}
