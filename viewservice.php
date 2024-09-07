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

    // Function to get all services
    public function getAllServices() {
        $sql = "SELECT * FROM services";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Service object
$service = new Service($conn);

// Fetch all services
$services = $service->getAllServices();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #272424;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 15px; 
            border-radius: 12px; 
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            width: 90%; 
            max-width: 600px; 
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 15px; 
            font-size: 22px; 
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px; 
            text-align: left;
            font-size: 14px; 
        }
        th {
            background-color: #ffbd04;
        }
        img {
            max-width: 80px; 
            height: auto;
        }
        p {
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Services</h2>
        <?php if (!empty($services)) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                </tr>
                <?php foreach ($services as $service) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service['id']); ?></td>
                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No services found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
