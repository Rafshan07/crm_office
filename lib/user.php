<?php
require_once 'database.php';

class User
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    // Login method for both staff and admin users
    public function login($email, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT UserID, Password, role FROM user WHERE email = :email LIMIT 1");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['Password'])) {
                // Start session and set session variables
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);

                $_SESSION['userid'] = $user['UserID'];
                $_SESSION['role'] = $user['role'];

                return $user['role']; // Return the role for redirection
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        return false; // Login failed
    }

    // Check if a customer is logging in
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

    // Fetch CustomerID for a given email
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

    // Fetch UserID for a given email (for staff/admin users)
    public function getUserIDByEmail($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT UserID FROM user WHERE email = :email LIMIT 1");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? $user['UserID'] : null;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        return null;
    }

    // Fetch user details by UserID
    public function getUserById($userId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE UserID = :id LIMIT 1");
            $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Return user data as an associative array
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

        return null; // Return null if no user is found
    }


    // Check if the user is logged in
    public function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['userid']);
    }
}
