<?php
// Database connection class
class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'restaurant';
    public $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }
}

// Staff class to handle staff registration
class Staff {
    private $db;
    private $firstname;
    private $lastname;
    private $district;
    private $gender;
    private $phone;
    private $dateOfBirth;
    private $address;
    private $email;
    private $password;

    public function __construct($firstname, $lastname, $district, $gender, $phone, $dateOfBirth, $address, $email, $password) {
        $this->db = new Database();
        $this->firstname = $this->sanitize($firstname);
        $this->lastname = $this->sanitize($lastname);
        $this->district = $this->sanitize($district);
        $this->gender = $this->sanitize($gender);
        $this->phone = $this->sanitize($phone);
        $this->dateOfBirth = $this->sanitize($dateOfBirth);
        $this->address = $this->sanitize($address);
        $this->email = $this->sanitize($email);
        $this->password = $this->sanitize($password);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Register new staff member
    public function register() {
        // Check if passwords match
        if ($this->password !== $_POST['repasswordh']) {
            return "Passwords do not match.";
        }

        // Hash the password
        $hashedPassword = md5($this->password);

        // Prepare the SQL statement
        $stmt = $this->db->conn->prepare("INSERT INTO staff (firstname, lastname, district, gender, phone, date_of_birth, address, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Check if the prepared statement was created successfully
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("sssssssss", $this->firstname, $this->lastname, $this->district, $this->gender, $this->phone, $this->dateOfBirth, $this->address, $this->email, $hashedPassword);

        if ($stmt->execute()) {
            $insertedId = $this->db->conn->insert_id; 
            return "Registration successful! Staff ID: " . $insertedId;
        } else {
            return "Error: " . htmlspecialchars($stmt->error);
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstnameh'];
    $lastname = $_POST['lastnameh'];
    $district = $_POST['districth'];
    $gender = $_POST['genderh'];
    $phone = $_POST['phonenoh'];
    $dateOfBirth = $_POST['dateofbirthh'];
    $address = $_POST['addressh'];
    $email = $_POST['emailh'];
    $password = $_POST['passwordh'];

    $staff = new Staff($firstname, $lastname, $district, $gender, $phone, $dateOfBirth, $address, $email, $password);
    $registrationMessage = $staff->register();

    echo $registrationMessage; 
}
?>
