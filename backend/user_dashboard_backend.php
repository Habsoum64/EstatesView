<?php
// Include database connection file
include '../settings/connection.php';
include '../settings/session.php';

$user_id = $_SESSION['user_id'];

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

// Function to get a listing's from the database
function getListing($listingId) {
    global $conn;
    $sql = "SELECT * FROM propertylistings WHERE listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}


// Function to edit a listing in the database
function editListing($listingId, $newData) {
    global $conn;
    $sql = "UPDATE propertylistings SET title=?, location=?, price=?, description=? WHERE listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $newData['title'], $newData['location'], $newData['price'], $newData['description'], $listingId);
    if ($stmt->execute()) {
        echo 'success';
    }
    $conn->close();
}

// Function to delete a listing from the database
function deleteListing($listingId) {
    global $conn;
    $sql = "DELETE FROM propertylistings WHERE listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    if ($stmt->execute()) {
        echo 'success';
    }
    $conn->close();
}

// Function to deactivate a listing in the database
function deactivateListing($listingId) {
    global $conn;
    $sql = "UPDATE propertylistings SET stat = 'Rejected' WHERE listing_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    if ($stmt->execute()) {
        echo 'success';
    }
    $conn->close();
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

// Function to fetch the favorite_id for a specific user and listing
function getFavoriteId($userId, $listingId) {
    global $conn;
    $sql = "SELECT favorite_id FROM favorites WHERE user_id = ? AND listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $listingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $favorite = $result->fetch_assoc(); // Fetch single row
    return $favorite['favorite_id'] ?? null; // Return the favorite_id or null
}

// Function to add a listing to user's saved favorites in the database
function addToFavorites($userId, $listingId) {
    global $conn;
    $sql = "INSERT INTO favorites (user_id, listing_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $listingId);
    if ($stmt->execute()) {
        echo 'success';
    }
    $conn->close();
}

// Function to remove a listing from user's saved favorites in the database
function removeFromFavorites($userId, $listingId) {
    global $conn;
    $favoriteId = getFavoriteId($userId, $listingId);
    if ($favoriteId !== null) {
        $sql = "DELETE FROM favorites WHERE favorite_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $favoriteId);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
    $conn->close();
}


$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'fetchUserDetails':
        fetchUserDetails($user_id);
        break;
    case 'fetchUserListings':
        fetchUserListings($user_id);
        break;
    case 'getListing':
        getListing($_POST['listing_id']);
        break;
    case 'editListing':
        editListing($_POST['listing_id'], $_POST['newData']);
        break;
    case 'deleteListing':
        deleteListing($_POST['listing_id']);
        break;
    case 'deactivateListing':
        deactivateListing($_POST['listing_id']);
        break;
    case 'fetchSavedFavorites':
        fetchSavedFavorites($user_id);
        break;
    case 'addToFavorites':
        addToFavorites($user_id, $_POST['listing_id']);
        break;
    case 'removeFavorite':
        removeFromFavorites($user_id, $_POST['listing_id']);
        break;        
}
