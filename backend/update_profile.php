<?php
include '../settings/session.php';
include '../settings/connection.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    
    // Prepare and execute SQL statement to update user's profile
    $sql = "UPDATE users SET email=?, age=?, gender=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $email, $age, $gender, $_SESSION['user_id']);
    $stmt->execute();

    // Update session variables with new profile information
    $_SESSION['email'] = $email;
    $_SESSION['age'] = $age;
    $_SESSION['gender'] = $gender;

    // Redirect back to the user dashboard with a success message
    header("Location: user_dashboard.php?update_success=1");
    exit();
} else {
    // Redirect to the user dashboard if accessed directly
    header("Location: user_dashboard.php");
    exit();
}
