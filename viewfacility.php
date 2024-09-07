<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Facilities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        img {
            border-radius: 5px;
            max-width: 150px; /* Adjust width as needed */
            height: auto;
        }

        p {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body background="contact.jpg">
    <div class="container">
        <h2>All Facilities</h2>
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

            // Retrieve all facilities
            public function getAllFacilities() {
                $sql = "SELECT * FROM facilities";
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

        // Create Facility object
        $facility = new Facility($conn);

        // Retrieve all facilities
        $facilities = $facility->getAllFacilities();

        // Display facilities
        if (!empty($facilities)) { ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                </tr>
                <?php foreach ($facilities as $facility) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($facility['id']); ?></td>
                        <td><?php echo htmlspecialchars($facility['name']); ?></td>
                        <td>
                            <?php if ($facility['image_path']) { ?>
                                <img src="uploads/<?php echo htmlspecialchars($facility['image_path']); ?>" alt="<?php echo htmlspecialchars($facility['name']); ?>">
                            <?php } else { ?>
                                No image
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No facilities found.</p>
        <?php }

        
        $conn->close();
        ?>
    </div>
</body>
</html>
