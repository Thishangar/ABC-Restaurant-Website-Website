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

// Staff class to handle login
class Staff {
    private $db;
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->db = new Database();
        $this->email = $this->sanitize($email);
        $this->password = $password; 
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Method to authenticate staff login
    public function login() {
        // Prepare the SQL statement to fetch user by email
        $stmt = $this->db->conn->prepare("SELECT id, password FROM staff WHERE email = ?");
        
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "Invalid email or password.";
        }

        $row = $result->fetch_assoc();
        $storedHashedPassword = $row['password'];
        
        // Verify the provided password with the stored hashed password
        if (password_verify($this->password, $storedHashedPassword)) {
            return "Login successful! Staff ID: " . $row['id'];
        } else {
            return "Invalid email or password.";
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['emailh'];
    $password = $_POST['passwordh'];

    $staff = new Staff($email, $password);
    $loginMessage = $staff->login();

    echo $loginMessage; 
}
?>
