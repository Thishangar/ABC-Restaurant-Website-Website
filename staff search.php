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

// Staff class to handle staff search
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

    // Search for staff member by ID
    public function search() {
        $stmt = $this->db->conn->prepare("SELECT * FROM staff WHERE id = ?");

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); 
            } else {
                return "No staff member found with ID: " . $this->id;
            }
        } else {
            return "Error: " . htmlspecialchars($stmt->error);
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['noh'];

    $staff = new Staff($id);
    $searchResult = $staff->search();

    if (is_array($searchResult)) {
        // Display staff details
        echo "<h1>Staff Details</h1>";
        echo "<p><strong>Staff ID:</strong> " . htmlspecialchars($searchResult['id']) . "</p>";
        echo "<p><strong>Firstname:</strong> " . htmlspecialchars($searchResult['firstname']) . "</p>";
        echo "<p><strong>Lastname:</strong> " . htmlspecialchars($searchResult['lastname']) . "</p>";
        echo "<p><strong>District:</strong> " . htmlspecialchars($searchResult['district']) . "</p>";
        echo "<p><strong>Gender:</strong> " . htmlspecialchars($searchResult['gender']) . "</p>";
        echo "<p><strong>Phone:</strong> " . htmlspecialchars($searchResult['phone']) . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($searchResult['date_of_birth']) . "</p>";
        echo "<p><strong>Address:</strong> " . htmlspecialchars($searchResult['address']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($searchResult['email']) . "</p>";
    } else {
        echo $searchResult; 
    }
}
?>
