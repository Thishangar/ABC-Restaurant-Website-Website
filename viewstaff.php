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

// Staff class to handle staff retrieval
class Staff {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Retrieve all staff members
    public function getAllStaff() {
        $stmt = $this->db->conn->prepare("SELECT * FROM staff");

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $staffList = [];
            while ($row = $result->fetch_assoc()) {
                $staffList[] = $row; // Collect all staff data into an array
            }
            return $staffList;
        } else {
            return "Error: " . htmlspecialchars($stmt->error);
        }
    }
}

// Fetch and display all staff members
$staff = new Staff();
$staffList = $staff->getAllStaff();

if (is_array($staffList)) {
    echo "<h1>Staff List</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>District</th><th>Gender</th><th>Phone</th><th>Date of Birth</th><th>Address</th><th>Email</th></tr>";
    foreach ($staffList as $staffMember) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($staffMember['id']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['firstname']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['lastname']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['district']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['gender']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['phone']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['date_of_birth']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['address']) . "</td>";
        echo "<td>" . htmlspecialchars($staffMember['email']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo $staffList; // Display error message
}
?>
