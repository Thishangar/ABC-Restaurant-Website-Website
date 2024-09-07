<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";


class Facility {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function addFacility($name, $imagePath) {
        $sql = "INSERT INTO facilities (name, image_path) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $name, $imagePath);

        return $stmt->execute();
    }

  
    public function uploadImage($imageFile) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($imageFile["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $uploadOk = 1;

        
        $check = getimagesize($imageFile["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

       
        if ($imageFile["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

       
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        
        if ($uploadOk == 0) {
            return false;
        } else {
          
            if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
                return basename($imageFile["name"]); 
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }
        }
    }
}


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['submit'])) {
    $name = $_POST['nameh'];
    $imagePath = "";

 
    $facility = new Facility($conn);

  
    if (isset($_FILES['imageh']) && $_FILES['imageh']['error'] == UPLOAD_ERR_OK) {
        $imagePath = $facility->uploadImage($_FILES['imageh']);
        if (!$imagePath) {
            
            exit;
        }
    }

    if ($facility->addFacility($name, $imagePath)) {
        echo "The facility has been added successfully.";
    } else {
        echo "Error adding facility.";
    }
}


$conn->close();
?>
