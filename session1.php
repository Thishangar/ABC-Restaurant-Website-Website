<?php 
session_start();
echo "You are Welcome " . htmlspecialchars($_SESSION['user']);
?>
<a href="stafflogout.php">Logout</a>
<a href="adminlogout.php">Logout</a>
