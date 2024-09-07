<?php

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


class ContactForm {
    private $db;
    private $name;
    private $email;
    private $message;

    public function __construct($name, $email, $message) {
        $this->db = new Database();
        $this->name = $this->sanitize($name);
        $this->email = $this->sanitize($email);
        $this->message = $this->sanitize($message);
    }

   
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

  
    public function save() {
        $stmt = $this->db->conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->name, $this->email, $this->message);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $contactForm = new ContactForm($name, $email, $message);

    if ($contactForm->save()) {
        echo "Thank you for contacting us!";
    } else {
        echo "There was an error submitting your message. Please try again.";
    }
}
?>
