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

// Reservation class to handle reservation operations
class Reservation {
    private $db;
    private $id;
    private $name;
    private $email;
    private $date;
    private $time;
    private $people;

    public function __construct($id, $name, $email, $date, $time, $people) {
        $this->db = new Database();
        $this->id = $this->sanitize($id);
        $this->name = $this->sanitize($name);
        $this->email = $this->sanitize($email);
        $this->date = $this->sanitize($date);
        $this->time = $this->sanitize($time);
        $this->people = $this->sanitize($people);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Method to update an existing reservation in the database
    public function update() {
        $stmt = $this->db->conn->prepare("UPDATE reservations SET name = ?, email = ?, date = ?, time = ?, people = ? WHERE id = ?");

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("ssssii", $this->name, $this->email, $this->date, $this->time, $this->people, $this->id);

        if ($stmt->execute()) {
            return "Reservation updated successfully!";
        } else {
            return "Error updating reservation: " . $stmt->error;
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['no']; 
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];

    $reservation = new Reservation($id, $name, $email, $date, $time, $people);
    $message = $reservation->update();

    echo $message; 
}
?>
