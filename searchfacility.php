<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Facility</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
            text-align: center;
        }


        .form-group input[type="text"] {
            width: 80%;
            padding: 10px;
            border-radius: 15px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
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
            max-width: 100px;
            height: auto;
        }

        .no-results {
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
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

            // Search facility by name or ID
            public function searchFacility($query) {
                $sql = "SELECT * FROM facilities WHERE name LIKE ? OR id = ?";
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

            // Create Facility object
            $facility = new Facility($conn);

            // Search for facilities
            $results = $facility->searchFacility($query);

            // Display search results
            if (!empty($results)) {
                echo "<h2>Search Results:</h2>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th><th>Image</th></tr>";
                foreach ($results as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td><img src='uploads/" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['name']) . "'></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-results'>No facilities found matching your query.</p>";
            }
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
