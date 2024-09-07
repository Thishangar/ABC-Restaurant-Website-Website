<?php
include 'Database.php';

class Contact {
    private $db;
    private $table = "contact";

    public function __construct() {
        $this->db = new Database();
    }

    public function addContact($id, $name, $email, $message) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO $this->table (no, name, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $name, $email, $message);

        if ($stmt->execute()) {
            echo "Add Query Successfully";
        } else {
            die("Error: " . $stmt->error);
        }

        $stmt->close();
    }
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['no'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $contact = new Contact();
    $contact->addContact($id, $name, $email, $message);
}
?>
