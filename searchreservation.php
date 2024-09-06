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
    private $no;

    public function __construct($no) {
        $this->db = new Database();  
        $this->no = $this->sanitize($no);  
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Method to search a reservation from the database
    public function search() {
        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("SELECT * FROM reservations WHERE id = ?");

        // Check if the prepared statement was successful
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("i", $this->no);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a matching reservation was found
        if ($result->num_rows > 0) {
            // Fetch the reservation details
            $reservation = $result->fetch_assoc();
            return $reservation;
        } else {
            return "No reservation found with the provided number.";
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['no']) && !empty($_POST['no'])) {
        $no = $_POST['no'];

        $reservation = new Reservation($no);
        $result = $reservation->search();

        if (is_array($result)) {
            // Display reservation details
            echo "<h2>Reservation Details:</h2>";
            echo "<p><strong>No:</strong> " . htmlspecialchars($result['id']) . "</p>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($result['name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($result['email']) . "</p>";
            echo "<p><strong>Date:</strong> " . htmlspecialchars($result['date']) . "</p>";
            echo "<p><strong>Time:</strong> " . htmlspecialchars($result['time']) . "</p>";
            echo "<p><strong>Number of People:</strong> " . htmlspecialchars($result['people']) . "</p>";
        } else {
            // Display the message if no reservation found
            echo $result;
        }
    } else {
        echo "No reservation number provided.";
    }
}
?>
