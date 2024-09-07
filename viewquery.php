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

// Contact class to handle fetching all contact details
class Contact {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Fetch all contact details
    public function fetchAll() {
        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("SELECT id, name, email, message FROM contacts");

        // Check if the prepared statement was created successfully
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch all data as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Creating a Contact object
$contact = new Contact();
$contacts = $contact->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC Restaurant - View Contacts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>All Contact Queries</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($contacts)) {
                foreach ($contacts as $contact) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($contact['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($contact['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($contact['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($contact['message']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No contacts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
