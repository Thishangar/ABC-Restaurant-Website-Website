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

// Staff class to handle staff operations
class Staff {
    private $db;
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $date_of_birth;
    private $phone;
    private $gender;
    private $address;
    private $district;
    private $password;

    public function __construct($id, $firstname, $lastname, $email, $date_of_birth, $phone, $gender, $address, $district, $password) {
        $this->db = new Database();
        $this->id = $this->sanitize($id);
        $this->firstname = $this->sanitize($firstname);
        $this->lastname = $this->sanitize($lastname);
        $this->email = $this->sanitize($email);
        $this->date_of_birth = $this->sanitize($date_of_birth);
        $this->phone = $this->sanitize($phone);
        $this->gender = $this->sanitize($gender);
        $this->address = $this->sanitize($address);
        $this->district = $this->sanitize($district);
        $this->password = $this->sanitize($password);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Check if the email already exists for another staff member
    private function emailExists($email, $id) {
        $stmt = $this->db->conn->prepare("SELECT id FROM staff WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Method to update an existing staff in the database
    public function update() {
        // Check if the email already exists for another staff member
        if ($this->emailExists($this->email, $this->id)) {
            return "Email already exists for another staff member!";
        }

        $stmt = $this->db->conn->prepare("UPDATE staff SET firstname = ?, lastname = ?, email = ?, date_of_birth = ?, phone = ?, gender = ?, address = ?, district = ?, password = ? WHERE id = ?");

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        // Hash the password before storing it
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bind_param("sssssssssi", $this->firstname, $this->lastname, $this->email, $this->date_of_birth, $this->phone, $this->gender, $this->address, $this->district, $hashed_password, $this->id);

        if ($stmt->execute()) {
            return "Staff updated successfully!";
        } else {
            return "Error updating staff: " . $stmt->error;
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['noh'];
    $firstname = $_POST['firstnameh'];
    $lastname = $_POST['lastnameh'];
    $email = $_POST['emailh'];
    $date_of_birth = $_POST['dateofbirthh'];
    $phone = $_POST['phonenoh'];
    $gender = $_POST['genderh'];
    $address = $_POST['addressh'];
    $district = $_POST['districth'];
    $password = $_POST['passwordh'];

    // Check if passwords match
    if ($password !== $_POST['repasswordh']) {
        echo "Passwords do not match!";
        exit;
    }

    $staff = new Staff($id, $firstname, $lastname, $email, $date_of_birth, $phone, $gender, $address, $district, $password);
    $message = $staff->update();

    echo $message; // Display the result message
}
?>
