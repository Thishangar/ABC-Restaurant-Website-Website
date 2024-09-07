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

// Contact class to handle contact deletion
class Contact {
    private $db;
    private $contactId;

    public function __construct($contactId) {
        $this->db = new Database();
        $this->contactId = $this->sanitize($contactId);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Delete contact by ID
    public function delete() {
        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("DELETE FROM contacts WHERE id = ?");

        // Check if the prepared statement was created successfully
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("i", $this->contactId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return "Contact with ID {$this->contactId} has been deleted successfully.";
            } else {
                return "No contact found with the given ID.";
            }
        } else {
            return "Error: " . htmlspecialchars($stmt->error);
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $contactId = $_POST['no'];

    $contact = new Contact($contactId);
    $deleteResult = $contact->delete();

    // Display the deletion result
    echo "<p>" . htmlspecialchars($deleteResult) . "</p>";
}
?>
