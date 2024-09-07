<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Service class
class Service {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to search service by name or ID
    public function searchService($query) {
        $sql = "SELECT * FROM services WHERE name LIKE ? OR id = ?";
        $stmt = $this->conn->prepare($sql);
        $likeQuery = "%" . $query . "%";
        $stmt->bind_param("si", $likeQuery, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search request
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Create Service object
    $service = new Service($conn);

    // Search for services
    $results = $service->searchService($query);

    // Display search results
    if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Image</th></tr>";
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td><img src='uploads/" . $row['image_path'] . "' alt='" . $row['name'] . "' width='100'></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No services found matching your query.";
    }
}

// Close connection
$conn->close();
?>
