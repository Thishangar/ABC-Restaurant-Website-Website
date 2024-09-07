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

    // Function to update service details
    public function updateService($id, $name, $imagePath) {
        $sql = "UPDATE services SET name = ?, image_path = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $imagePath, $id);
        
        return $stmt->execute();
    }

    // Function to get the current image path of a service by ID
    public function getServiceById($id) {
        $sql = "SELECT image_path FROM services WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceId = $_POST['serviceId'];
    $serviceName = $_POST['serviceName'];
    $targetFile = "";
    $uploadOk = 1;

    // Create Service object
    $service = new Service($conn);

    // Check if a new image was uploaded
    if (isset($_FILES['serviceImage']) && $_FILES['serviceImage']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . basename($_FILES["serviceImage"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        $check = getimagesize($_FILES["serviceImage"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

       
        if ($_FILES["serviceImage"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES["serviceImage"]["tmp_name"], $targetFile)) {
                // Successfully uploaded the new image
                $imagePath = basename($_FILES["serviceImage"]["name"]); 
            } else {
                echo "Sorry, there was an error uploading your file.";
                $imagePath = ""; 
            }
        }
    } else {
        // No new image was uploaded, use the existing image
        $serviceData = $service->getServiceById($serviceId);
        $imagePath = $serviceData['image_path']; 
    }

    // Update the service in the database
    if ($uploadOk == 1 && $service->updateService($serviceId, $serviceName, $imagePath)) {
        echo "The service has been updated successfully.";
    } else {
        echo "Error updating service.";
    }
}

// Close connection
$conn->close();
?>
