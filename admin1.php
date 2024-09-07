<?php
session_start();

class SessionManager {
    public function getUsername() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}


$sessionManager = new SessionManager();


if ($sessionManager->isLoggedIn()) {
    echo "You are Welcome, " . $sessionManager->getUsername() . "<br>";
    echo "<a href='logout.php'>Logout</a>";
} else {
    echo "You are not logged in.";
}
?>
