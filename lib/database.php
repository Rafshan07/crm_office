<?php
include 'config.php';
class database
{
    public $db_host = DB_HOST;
    public $db_user = DB_USER;
    public $db_pass = DB_PASS;
    public $db_name = DB_NAME;

    public $pdo;
    public $error;

    public function __construct()
    {
        $this->connectDB();
    }

    private function connectDB()
    {
        if (!isset($this->pdo)) {
            try {
                $this->pdo = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection Failed! " . $e->getMessage());
            }
        }
    }
    public function select($query, $params = [])
    {
        try {
            // Prepare the query
            $stmt = $this->pdo->prepare($query);

            // Bind the parameters if there are any
            if (!empty($params)) {
                foreach ($params as $key => $param) {
                    // Check if $key is numeric, which indicates positional placeholders
                    if (is_numeric($key)) {
                        $stmt->bindValue($key + 1, $param); // Positional binding (starts from 1)
                    } else {
                        $stmt->bindValue($key, $param); // Named placeholder binding
                    }
                }
            }

            // Execute the query
            $stmt->execute();

            // Return the result if rows exist
            if ($stmt->rowCount() > 0) {
                return $stmt;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("Query failed! " . $e->getMessage());
        }
    }

    public function insert($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);  // Prepare the query with placeholders
            $stmt->execute($params);  // Execute the query with the actual parameters
            return $this->pdo->lastInsertId();  // Get the last inserted ID
        } catch (PDOException $e) {
            die("Insertion failed! " . $e->getMessage());
        }
    }


    public function update($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);  // Prepare the query with placeholders
            $stmt->execute($params);  // Execute the query with the actual parameters
            return true;  // Return true if the update is successful
        } catch (PDOException $e) {
            die("Update failed! " . $e->getMessage());
        }
    }


    public function delete($query)
    {
        try {
            $this->pdo->exec($query);
            return true;
        } catch (PDOException $e) {
            die("Deletion failed! " . $e->getMessage());
        }
    }
}
