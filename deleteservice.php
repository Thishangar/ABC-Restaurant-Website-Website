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

    // Function to delete a service by ID
    public function deleteService($id) {
        // First, retrieve the image path to delete the associated image file
        $sql = "SELECT image_path FROM services WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();

        if ($service) {
            $imagePath = $service['image_path'];
            // Delete the service from the database
            $sql = "DELETE FROM services WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // If the service was deleted, delete the image file as well
                if (!empty($imagePath) && file_exists("uploads/" . $imagePath)) {
                    unlink("uploads/" . $imagePath);
                }
                return true;
            }
        }
        return false;
    }
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['serviceId'];

    // Create Service object
    $service = new Service($conn);

    // Attempt to delete the service
    if ($service->deleteService($serviceId)) {
        echo "The service has been deleted successfully.";
    } else {
        echo "Error deleting service. Please make sure the service ID is correct.";
    }
}

// Close connection
$conn->close();
?>
