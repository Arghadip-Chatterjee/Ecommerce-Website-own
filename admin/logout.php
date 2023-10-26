<?php
// Start the session
session_start();

// Unset all session values
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or the main page of your website
header("Location: index.php");
exit;
?>
