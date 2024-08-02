<?php
// Include session.php
include '../settings/session.php';
// Include connection.php
include '../settings/connection.php';

// Redirect to homepage if already logged-in
redirect_if_logged_in();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $user_type = 'Admin';

    // Initialize an array to store validation errors
    $errors = [];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($gender) || empty($dob)) {
        $errors[] = "Please fill in all fields.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Perform password strength validation using regex
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[a-zA-Z\d\W_]{8,}$/", $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one digit, and one special character.";
    }

    // Check if there are any validation errors
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, passwd, gender, date_of_birth, user_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $gender, $dob, $user_type);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. You can now log in.";
            header("Location: ../login/login.php");
            exit();
        } else {
            $_SESSION['error'] = "Error occurred. Please try again later.";
            header("Location: register.php");
            exit();
        }
    } else {
        // If there are validation errors, store them in the session and redirect back to register.php
        $_SESSION['errors'] = $errors;
        //header("Location: register.php");
        exit();
    }
}
