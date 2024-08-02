<?php
// Include session.php and connection.php
include '../settings/session.php';
include '../settings/connection.php';

redirect_if_logged_in();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $user_type = 'Regular';

    // Initialize an array to store validation errors
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Username already taken.";
        }
        $stmt->close();
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $stmt->close();
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one digit, and one special character.";
    }

    // Validate gender
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }

    // If there are no validation errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, passwd, gender, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $gender, $user_type);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Error occurred. Please try again later.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    header("Location: register.php");
    exit();
}
