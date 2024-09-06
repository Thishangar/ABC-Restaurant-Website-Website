<?php
// Database connection class
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'restaurant';
    public $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }
}

// Staff class to handle staff operations
class Staff {
    private $db;
    private $id;

    public function __construct($id) {
        $this->db = new Database();
        $this->id = $this->sanitize($id);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Method to delete an existing staff from the database
    public function delete() {
        $stmt = $this->db->conn->prepare("DELETE FROM staff WHERE id = ?");

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return "Staff deleted successfully!";
        } else {
            return "Error deleting staff: " . $stmt->error;
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['noh'];

    if (!empty($id)) {
        $staff = new Staff($id);
        $message = $staff->delete();
    } else {
        $message = "Staff NO is required.";
    }

    echo $message; 
}
?>
