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

    // Update facility details including image path
    public function updateFacility($id, $name, $imagePath) {
        $sql = "UPDATE facilities SET name = ?, image_path = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $imagePath, $id);
        return $stmt->execute();
    }

    // Get current image path by ID
    public function getFacilityById($id) {
        $sql = "SELECT image_path FROM facilities WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Validate and upload the image file
    public function uploadImage($imageFile) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($imageFile["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check if image file is a real image
        $check = getimagesize($imageFile["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (5MB limit)
        if ($imageFile["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
        } else {
            // Attempt to move the uploaded file
            if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
                return basename($imageFile["name"]); // Store only the file name in the database
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }
        }
    }
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $id = $_POST['idh'];
    $name = $_POST['nameh'];
    $imagePath = "";

    // Create Facility object
    $facility = new Facility($conn);

    // Check if a file was uploaded and process it
    if (isset($_FILES['imageh']) && $_FILES['imageh']['error'] == UPLOAD_ERR_OK) {
        $imagePath = $facility->uploadImage($_FILES['imageh']);
        if (!$imagePath) {
            // If image upload failed, stop further processing
            exit;
        }
    } else {
        // No new image was uploaded, retain the existing image path
        $facilityData = $facility->getFacilityById($id);
        $imagePath = $facilityData['image_path'];
    }

    // Update facility in the database
    if ($facility->updateFacility($id, $name, $imagePath)) {
        echo "The facility has been updated successfully.";
    } else {
        echo "Error updating facility.";
    }
}

// Close connection
$conn->close();
?>
