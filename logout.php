<?php
// Start the session
session_start();

// If the user is logged in, unset the session
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

// Redirect to login page
header('Location: login.html');

// Stop further script execution
exit;
?>
