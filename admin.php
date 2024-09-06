<?php
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


class Admin {
    private $db;
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->db = new Database();
        $this->username = $this->sanitize($username);
        $this->password = $this->sanitize($password);
    }


    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }


    public function login() {

        $stmt = $this->db->conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        

        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($this->db->conn->error));
        }

        $stmt->bind_param("ss", $this->username, $this->password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            session_start();
            $_SESSION['admin'] = $this->username;
            header("Location: admin panal.html"); 
            exit();
        } else {
      
            return "Invalid username or password.";
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usernameh'];
    $password = $_POST['passwordh'];

    $admin = new Admin($username, $password);
    $loginMessage = $admin->login();

    if ($loginMessage) {
        echo $loginMessage; 
    }
}
?>
