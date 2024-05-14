<?php
// Include necessary files and database connection
include '../settings/session.php';
include '../settings/connection.php';

// Fetch property details from the database based on the listing ID
if (isset($_GET['listing_id'])) {
    $listing_id = $_GET['listing_id'];
    
    // Query to fetch property details
    $sql = "SELECT * FROM propertylistings WHERE listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listing_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $property = $result->fetch_assoc();
        
        // Fetch images associated with the listing
        $sql_images = "SELECT image_path FROM listing_images WHERE listing_id = ?";
        $stmt_images = $conn->prepare($sql_images);
        $stmt_images->bind_param("i", $listing_id);
        $stmt_images->execute();
        $result_images = $stmt_images->get_result();
        
        $images = array();
        while ($row = $result_images->fetch_assoc()) {
            $images[] = $row['image_path'];
        }
    } else {
        // Redirect to error page or display error message
    }
} else {
    // Redirect to error page or display error message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles for Slideshow -->
    <style>
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }
        .mySlides {
            display: none;
        }
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }
        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }
        .dots {
            text-align: center;
            padding-top: 20px;
        }
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }
        .active, .dot:hover {
            background-color: #717171;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">Estates View</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>

                    <!-- Conditional behavior based on user status -->
                    <?php if (isset($_SESSION['user_id'])) { // If user is logged in ?>
                        <?php if ($_SESSION['user_type'] == 'admin') { // If user is an admin ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
                            </li>
                        <?php } else { // If user is not an admin ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user_dashboard.php">User Dashboard</a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/logout.php">Logout</a>
                        </li>
                    <?php } else { // If user is not logged in ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/register.php">Register</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center text-primary">Property Details</h1>

        <div class="row">
            <div class="col-md-6">
                <h2><?php echo $property['title']; ?></h2>
                <p><strong>Location:</strong> <?php echo $property['location']; ?></p>
                <p><strong>Price:</strong> $<?php echo $property['price']; ?></p>
                <p><strong>Description:</strong> <?php echo $property['description']; ?></p>
            </div>
            <div class="col-md-6">
                <!-- Slideshow for listing images -->
                <div class="slideshow-container">
                    <?php if (!empty($images)) { ?>
                        <?php foreach ($images as $key => $image) { ?>
                            <div class="mySlides">
                                <div class="numbertext"><?php echo $key + 1; ?> / <?php echo count($images); ?></div>
                                <img src="<?php echo $image; ?>" style="width:100%">
                            </div>
                        <?php } ?>
                        <!-- Prev/Next controls -->
                        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                        <a class="next" onclick="plusSlides(1)">&#10095;</a>
                        <!-- Dots -->
                        <div class="dots">
                            <?php foreach ($images as $key => $image) { ?>
                                <span class="dot" onclick="currentSlide(<?php echo $key + 1; ?>)"></span>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <p>No images available for this listing.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Back to Listings Button -->
        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to Listings</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include JavaScript for slideshow functionality -->
    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }
    </script>
</body>
</html>
