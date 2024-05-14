<?php
// Include session start or initialization file
include '../settings/session.php';

// Unset or destroy session variables related to login status
unset($_SESSION['user_id']);
unset($_SESSION['user_type']);

// Destroy the session (optional)
session_destroy();

// Redirect the user to the login page or any other appropriate page
header("Location: login.php");
exit();
