<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Facility class
class Facility {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Delete facility by ID
    public function deleteFacility($id) {
        // First, delete the image from the server if necessary
        $imagePath = $this->getFacilityImagePathById($id);
        if ($imagePath) {
            $filePath = "uploads/" . $imagePath;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Then, delete the facility record from the database
        $sql = "DELETE FROM facilities WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    // Get the image path of a facility by ID
    private function getFacilityImagePathById($id) {
        $sql = "SELECT image_path FROM facilities WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $facility = $result->fetch_assoc();
        return $facility ? $facility['image_path'] : null;
    }
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Create Facility object
    $facility = new Facility($conn);

    // Attempt to delete the facility
    if ($facility->deleteFacility($id)) {
        echo "The facility has been deleted successfully.";
    } else {
        echo "Error deleting facility.";
    }
}

// Close connection
$conn->close();
?>
