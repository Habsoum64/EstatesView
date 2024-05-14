<?php
include '../settings/session.php';
include '../settings/connection.php'; // Include the database connection file

// Check if the user is an admin
if ($_SESSION['user_type'] !== 'admin') {
    // Redirect to the user dashboard if the user is not an admin
    header("Location: user_dashboard.php");
    exit();
}

// Check if listing approval action is triggered
if (isset($_GET['action']) && isset($_GET['listing_id'])) {
    $action = $_GET['action'];
    $listing_id = $_GET['listing_id'];
    
    if ($action === 'approve') {
        // Update listing approval status to approved in the database
        $sql = "UPDATE propertylistings SET approved = 1 WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listing_id);
        $stmt->execute();
    } elseif ($action === 'reject') {
        // Delete listing from the database
        $sql = "DELETE FROM propertylistings WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listing_id);
        $stmt->execute();
    }
}

// Fetch new listings awaiting approval from the database
$sql = "SELECT * FROM propertylistings WHERE approved = 0";
$result = $conn->query($sql);

// Fetch user information for each listing
$listings = array();
while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $user_sql = "SELECT username FROM users WHERE user_id = $user_id";
    $user_result = $conn->query($user_sql);
    $user_row = $user_result->fetch_assoc();
    $row['username'] = $user_row['username'];
    $listings[] = $row;
}
