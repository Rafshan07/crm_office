<?php
class database {
    public $db_host = DB_HOST;
    public $db_user = DB_USER;
    public $db_pass = DB_PASS;
    public $db_name = DB_NAME;

    public $pdo;
    public $error;

    public function __construct() {
        $this->connectDB();
    }

    private function connectDB() {
        if (!isset($this->pdo)) {
            try {
                $this->pdo = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection Failed! " . $e->getMessage());
            }
        }
    }

    public function insert($query) {
        try {
            $insert_row = $this->pdo->query($query);
            if ($insert_row) {
                header("Location: index.php?msg=" . urlencode('Data Inserted Successfully!'));
            }
        } catch (PDOException $e) {
            die("Insertion failed! " . $e->getMessage());
        }
    }
}
?>
