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

// Contact class to handle contact search
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

    // Search for contact details by contact ID
    public function search() {
        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("SELECT * FROM contacts WHERE id = ?");

        // Check if the prepared statement was created successfully
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("i", $this->contactId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Check if any result was returned
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                return $data;
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
    $searchResult = $contact->search();

    // Display the search result
    if (is_array($searchResult)) {
        echo "<h2>Contact Details:</h2>";
        echo "<p><b>Contact ID:</b> " . htmlspecialchars($searchResult['id']) . "</p>";
        echo "<p><b>Name:</b> " . htmlspecialchars($searchResult['name']) . "</p>";
        echo "<p><b>Email:</b> " . htmlspecialchars($searchResult['email']) . "</p>";
        echo "<p><b>Message:</b> " . htmlspecialchars($searchResult['message']) . "</p>";
    } else {
        echo "<p>" . htmlspecialchars($searchResult) . "</p>";
    }
}
?>
