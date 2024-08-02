<?php
include '../settings/session.php';
include '../settings/connection.php';

check_login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $listing_id = $_POST['listing_id'];

    // Check if the user is logged in and has valid session
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
        // Insert favorite into the database
        $sql = "INSERT INTO favorites (user_id, listing_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $listing_id);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
}
