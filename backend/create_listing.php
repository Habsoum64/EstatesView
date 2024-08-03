<?php
include '../settings/session.php';
include '../settings/connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Upload images
    $target_directory = "../assets/uploads/"; // Directory where images will be stored
    $target_files = array();
    foreach ($_FILES['images']['name'] as $key => $image_name) {
        $target_file = $target_directory . basename($_FILES['images']['name'][$key]);
        if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
            $target_files[] = $target_file;
        }
    }

    // Insert listing details into the database
    $sql = "INSERT INTO propertylistings (title, location, price, description, cover, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $title, $location, $price, $description, $target_files[0], $_SESSION['user_id']);
    $stmt->execute();

    // Get the ID of the last inserted listing
    $listing_id = $stmt->insert_id;

    // Insert image paths into the database
    foreach ($target_files as $image_path) {
        $sql = "INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $listing_id, $image_path);
        $stmt->execute();
    }

    // Redirect to user dashboard with success message
    header("Location: ../view/user_dashboard.php?success=1");
    exit();
}
