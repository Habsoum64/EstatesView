<?php
// Include database connection file
include '../settings/connection.php';

// Include database connection file
include '../settings/connection.php';

// Function to fetch user's details from the database
function fetchUserDetails($userId) {
    global $conn;
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to fetch user's listings from the database
function fetchUserListings($userId) {
    global $conn;
    $sql = "SELECT * FROM propertylistings WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to edit a listing in the database
function editListing($listingId, $newData) {
    global $conn;
    $sql = "UPDATE propertylistings SET title=?, location=?, price=?, description=? WHERE listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $newData['title'], $newData['location'], $newData['price'], $newData['description'], $listingId);
    return $stmt->execute();
}

// Function to delete a listing from the database
function deleteListing($listingId) {
    global $conn;
    $sql = "DELETE FROM propertylistings WHERE listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    return $stmt->execute();
}

// Function to deactivate a listing in the database
function deactivateListing($listingId) {
    global $conn;
    $sql = "UPDATE propertylistings SET active=0 WHERE listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    return $stmt->execute();
}

// Function to fetch user's saved favorites from the database
function fetchSavedFavorites($userId) {
    global $conn;
    $sql = "SELECT l.* FROM propertylistings l INNER JOIN favorites sf ON l.listing_id = sf.listing_id WHERE sf.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to add a listing to user's saved favorites in the database
function addToFavorites($userId, $listingId) {
    global $conn;
    $sql = "INSERT INTO favorites (user_id, listing_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $listingId);
    return $stmt->execute();
}

// Function to remove a listing from user's saved favorites in the database
function removeFromFavorites($userId, $listingId) {
    global $conn;
    $sql = "DELETE FROM favorites WHERE user_id=? AND listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $listingId);
    return $stmt->execute();
}

// Function to update user profile information in the database
function updateUserProfile($userId, $newProfileData) {
    global $conn;
    $sql = "UPDATE users SET email=?, age=?, gender=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $newProfileData['email'], $newProfileData['age'], $newProfileData['gender'], $userId);
    return $stmt->execute();
}
