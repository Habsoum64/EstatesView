<?php
// Function to send email to the seller
function sendEmail($seller_email, $sender_email, $message) {
    $subject = "Message from Real Estate Platform";
    $headers = "From: $sender_email";
    $body = "You have received a message from a user:\n\n$message";
    return mail($seller_email, $subject, $body, $headers);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $seller_email = $_POST['seller_email']; // Seller's email passed as a hidden input field in the form
    $sender_email = $_POST['email'];
    $message = $_POST['message'];

    if (sendEmail($seller_email, $sender_email, $message)) {
        $success_message = "Your message has been sent successfully!";
    } else {
        $error_message = "Failed to send message. Please try again later.";
    }
}

// Redirect back to the property details page after processing form submission
header("Location: property_details.php?listing_id=" . $_POST['listing_id']);
exit();
