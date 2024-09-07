<?php
session_start();

class Database {
    private $pdo;

    public function __construct($dsn, $username, $password) {
        try {
            // Establish a PDO connection
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function authenticate($username, $password) {
        $sql = "SELECT no FROM login WHERE uname = :username AND password = :password";
        $params = [
            ':username' => $username,
            ':password' => $password
        ];
        $stmt = $this->db->query($sql, $params);
        return $stmt->rowCount() > 0;
    }
}

// Initialize the database connection
$dsn = "mysql:host=localhost;dbname=your_database_name;charset=utf8";
$db = new Database($dsn, "root", "");

// Get POST data
$username = $_POST["unameh"];
$password = $_POST["hpass"];

// Create a User object and authenticate the user
$user = new User($db);

if ($user->authenticate($username, $password)) {
    $_SESSION['user'] = $username;
    header("Location: admin.php");
    exit;
} else {
    echo "Sorry, there is no username $username with the specified password.";
    echo "Try again";
}
?>
