<?php
include '../settings/session.php';
include '../settings/connection.php';

redirect_if_logged_in();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter both username and password.";
        header("Location: login.php");
        exit();
    }

    // Perform login authentication
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['passwd'])) {
            // Authentication successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['username'] = $username;
            header("Location: ../view/home.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        // Username not found
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}
