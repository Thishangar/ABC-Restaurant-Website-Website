<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";


class Service {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

 
    public function addService($name, $imagePath) {
        $sql = "INSERT INTO services (name, image_path) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $name, $imagePath);
        
        return $stmt->execute();
    }
}


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serviceName = $_POST['serviceName'];
    $imagePath = "";
    $uploadOk = 1;

 
    $service = new Service($conn);

    
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

       
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
   
            if (move_uploaded_file($_FILES["serviceImage"]["tmp_name"], $targetFile)) {
            
                $imagePath = basename($_FILES["serviceImage"]["name"]); 
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

 
    if ($uploadOk == 1 && $service->addService($serviceName, $imagePath)) {
        echo "The service has been added successfully.";
    } else {
        echo "Error adding service.";
    }
}


$conn->close();
?>
