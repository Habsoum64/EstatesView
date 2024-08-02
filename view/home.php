<?php
// Include necessary files and database connection
include '../settings/connection.php';
include '../settings/session.php';

// Fetch random listings from the database
$sql_random_listings = "SELECT * FROM propertylistings ORDER BY RAND() LIMIT 8";
$result_random_listings = $conn->query($sql_random_listings);

// Handle search functionality
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_query = $_GET['search'];
    // Query to search listings by location
    $sql_search_listings = "SELECT * FROM propertylistings WHERE location LIKE '%$search_query%'";
    $result_search_listings = $conn->query($sql_search_listings);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Platform</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php' ?>

    <div class="container mt-4">
        <h1 class="text-center text-primary">Welcome to Real Estate Platform</h1>
        
        <!-- Search Form -->
        <form class="mt-4 mb-4" method="GET" action="home.php">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Search by location">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Search Results Section -->
        <?php if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) { ?>
            <h2 class="text-center text-primary mb-4">Search Results</h2>
            <?php if ($result_search_listings->num_rows > 0) {
                while ($row = $result_search_listings->fetch_assoc()) { ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?php echo $row['title']; ?></h5>
                            <p class="card-text">Location: <?php echo $row['location']; ?></p>
                            <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                            <a href="property_details.php?listing_id=<?php echo $row['listing_id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <p class="text-center text-danger">No search results found.</p>
            <?php } ?>
        <?php } ?>

        <hr>

        <!-- Random Listings Section -->
        <h2 class="text-center text-primary mb-4">Random Listings</h2>
        <div class="row">
            <?php if ($result_random_listings->num_rows > 0) {
                while ($row = $result_random_listings->fetch_assoc()) { ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?php echo $row['cover']; ?>" class="card-img-top" alt="Listing Image">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo $row['title']; ?></h5>
                                <p class="card-text">Location: <?php echo $row['location']; ?></p>
                                <p class="card-text">Price: $<?php echo $row['price']; ?></p>
                                <a href="property_details.php?listing_id=<?php echo $row['listing_id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <p class="text-center text-danger">No listings available.</p>
            <?php } ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
