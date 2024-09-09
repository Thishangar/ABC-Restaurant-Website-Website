<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Database connection class using OOP
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

// Reservation class to handle reservation submissions
class Reservation {
    private $db;
    private $name;
    private $email;
    private $date;
    private $time;
    private $people;

    public function __construct($name, $email, $date, $time, $people) {
        $this->db = new Database();
        $this->name = $this->sanitize($name);
        $this->email = $this->sanitize($email);
        $this->date = $this->sanitize($date);
        $this->time = $this->sanitize($time);
        $this->people = $this->sanitize($people);
    }

    // Sanitize user input to prevent SQL injection
    private function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    // Method to save the reservation data into the database
    public function save() {
        $stmt = $this->db->conn->prepare("INSERT INTO reservations (name, email, date, time, people) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $this->name, $this->email, $this->date, $this->time, $this->people);

        return $stmt->execute();
    }

    // Method to send confirmation email using PHPMailer
    public function sendEmail() {
        $mail = new PHPMailer(true);
        try {
            // SMTP server configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yoganathanthishangar@gmail.com';  // Your Gmail address
            $mail->Password = 'mlvz jdnv hsyb gkry';  // Your Gmail app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('yoganathanthishangar@gmail.com', 'ABC Restaurant'); // Sender's email and name
            $mail->addAddress($this->email);  // Recipient email (the user's email)
            $mail->addReplyTo('yoganathanthishangar@gmail.com', 'ABC Restaurant');  // Reply-To email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Your Reservation Confirmation';
            $mail->Body = "<strong>Dear {$this->name},</strong><br><br>
                          Thank you for your reservation at ABC Restaurant. Here are your reservation details:<br>
                          <strong>Date:</strong> {$this->date}<br>
                          <strong>Time:</strong> {$this->time}<br>
                          <strong>Number of People:</strong> {$this->people}<br><br>
                          We look forward to serving you.<br><br>
                          Best regards,<br>
                          ABC Restaurant";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Processing the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];

    $reservation = new Reservation($name, $email, $date, $time, $people);
    if ($reservation->save()) {
        if ($reservation->sendEmail()) {
            echo "Your reservation has been successfully submitted and a confirmation email has been sent!";
        } else {
            echo "Your reservation has been submitted, but there was an error sending the confirmation email.";
        }
    } else {
        echo "There was an error submitting your reservation. Please try again.";
    }
}
?>
