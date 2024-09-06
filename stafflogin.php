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

// Staff class to handle staff login
class Staff {
    private $db;
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->db = new Database();
        $this->email = $this->sanitize($email);
        $this->password = $this->sanitize($password);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Login method
    public function login() {
        // Hash the password for comparison
        $hashedPassword = md5($this->password);

        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("SELECT * FROM staff WHERE email = ? AND password = ?");
        
        // Check if the prepared statement was created successfully
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("ss", $this->email, $hashedPassword);

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a matching staff member was found
        if ($result->num_rows > 0) {
            // Start session and store login information if needed
            session_start();
            $_SESSION['staff_email'] = $this->email;
            header("Location: staff panal.html"); 
            exit();
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
