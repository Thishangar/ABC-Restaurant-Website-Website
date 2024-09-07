<?php
// Database configuration and connection
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
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}

// Customer class
class Customer {
    private $conn;
    private $table = 'customers';

    public $firstname;
    public $lastname;
    public $district;
    public $gender;
    public $phone;
    public $dob;
    public $address;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if email already exists
    public function emailExists() {
        $sql = "SELECT id FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $this->email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Register a new customer
    public function register() {
        if ($this->emailExists()) {
            return false;  
        }

        $sql = "INSERT INTO " . $this->table . " (firstname, lastname, district, gender, phone, dob, address, email, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT); 

        if ($stmt) {
            $stmt->bind_param(
                'sssssssss',
                $this->firstname,
                $this->lastname,
                $this->district,
                $this->gender,
                $this->phone,
                $this->dob,
                $this->address,
                $this->email,
                $hashedPassword
            );
            return $stmt->execute();
        } else {
            return false;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->conn;

    $customer = new Customer($db);

    $customer->firstname = $_POST['firstnameh'];
    $customer->lastname = $_POST['lastnameh'];
    $customer->district = $_POST['districth'];
    $customer->gender = $_POST['genderh'];
    $customer->phone = $_POST['phonenoh'];
    $customer->dob = $_POST['dateofbirthh'];
    $customer->address = $_POST['addressh'];
    $customer->email = $_POST['emailh'];
    $customer->password = $_POST['passwordh'];

    if ($customer->password !== $_POST['repasswordh']) {
        die('Passwords do not match.');
    }

    if ($customer->register()) {
        echo 'Registration successful!';
    } else {
        echo 'Registration failed. Email already exists or another error occurred.';
    }
}
?>
