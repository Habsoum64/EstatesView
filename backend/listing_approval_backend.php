<?php
include '../settings/connection.php'; // Include the database connection file

// Check if listing approval action is triggered
if (isset($_GET['action']) && isset($_GET['listing_id'])) {
    $action = $_GET['action'];
    $listing_id = $_GET['listing_id'];
    $sql = "";

    if ($action === 'approve') {
        // Update listing approval status to approved in the database
        $sql = "UPDATE propertylistings SET stat = 'Approved' WHERE listing_id = ?";
    } elseif ($action === 'reject') {
        // Delete listing from the database
        $sql = "UPDATE propertylistings SET stat = 'Rejected' WHERE listing_id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listing_id);
    if ($stmt->execute()) {
        echo 'success';
    }
    else {
        echo 'error';
    }
}

// Fetch new listings awaiting approval from the database
$sql = "SELECT * FROM propertylistings";
$listings = $conn->query($sql);
