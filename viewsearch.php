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
        // Create a new mysqli object for database connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check if connection was successful
        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }
}

// Reservation class to handle reservation operations
class Reservation {
    private $db;

    public function __construct() {
        $this->db = new Database();  
    }

    // Method to get all reservations from the database
    public function getAllReservations() {
        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("SELECT * FROM reservations");

        // Check if the prepared statement was successful
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all reservations
        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }

        return $reservations;
    }
}

// Create an instance of the Reservation class and get all reservations
$reservation = new Reservation();
$reservations = $reservation->getAllReservations();

// Output reservations in JSON format (for API or AJAX usage)
header('Content-Type: application/json');
echo json_encode($reservations);
?>
