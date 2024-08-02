<?php
include '../settings/connection.php'; // Include the database connection file

// Check if user management action is triggered
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $action = $_GET['action'];
    $user_id = $_GET['user_id'];
    
    if ($action === 'delete') {
        // Delete user account from the database
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
}

// Fetch user accounts from the database
$user_sql = "SELECT * FROM users";
$user_result = $conn->query($user_sql);
$users = array();
while ($row = $user_result->fetch_assoc()) {
    $users[] = $row;
}
