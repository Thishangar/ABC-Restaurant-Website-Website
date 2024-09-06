<?php
// Database connection class using OOP
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

// Reservation class to handle reservation submissions
class Reservation {
    private $db;
    private $name;
    private $email;
    private $date;
    private $time;
    private $people;

    public function __construct($name, $email, $date, $time, $people) {
        $this->db = new Database();
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

    // Method to save the reservation data into the database
    public function save() {
        $stmt = $this->db->conn->prepare("INSERT INTO reservations (name, email, date, time, people) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $this->name, $this->email, $this->date, $this->time, $this->people);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];

    $reservation = new Reservation($name, $email, $date, $time, $people);

    if ($reservation->save()) {
        echo "Your reservation has been successfully submitted!";
    } else {
        echo "There was an error submitting your reservation. Please try again.";
    }
}
?>
