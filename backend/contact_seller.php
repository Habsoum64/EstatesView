<?php
include '../settings/session.php';
include '../settings/connection.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $listingId = $_POST['listing_id'];
    $message = $_POST['message'];

    // Fetch seller's email from the listings table
    $sql = "SELECT u.email FROM propertylistings l JOIN users u ON l.user_id = u.user_id WHERE l.listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $sellerEmail = $row['email'];

    // Send email to seller
    $subject = "Regarding Your Listing";
    $body = "Message from: ".$_SESSION['email']."\n".$message;
    $headers = "From: ".$_SESSION['email'];

    if (mail($sellerEmail, $subject, $body, $headers)) {
        // Email sent successfully
        header("Location: user_dashboard.php?contact_success=1");
        exit();
    } else {
        // Error sending email
        header("Location: user_dashboard.php?contact_error=1");
        exit();
    }
} else {
    // Redirect to the user dashboard if accessed directly
    header("Location: user_dashboard.php");
    exit();
}
