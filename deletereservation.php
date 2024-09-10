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

    // Method to delete a reservation by its ID
    public function deleteReservation($reservation_id) {
        // Prepare the SQL statement with the correct column name
        $stmt = $this->db->conn->prepare("DELETE FROM reservations WHERE id = ?");
        
        // Check if the prepared statement was successful
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        // Bind the reservation ID parameter to the SQL statement
        $stmt->bind_param('s', $reservation_id);

        // Execute the statement
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;  
        } else {
            return false;
        }

        // Close the statement
        $stmt->close();
    }
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the reservation ID from the form
    $reservation_id = $_POST['reservation_no']; 

    // Create an instance of the Reservation class
    $reservation = new Reservation();

    // Attempt to delete the reservation
    if ($reservation->deleteReservation($reservation_id)) {
        echo "<script>alert('Reservation deleted successfully.'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Reservation not found or deletion failed.'); window.location.href='deletereservation.html';</script>";
    }
}
?>
