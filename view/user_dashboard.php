<?php
include '../settings/session.php';
include '../backend/user_dashboard_backend.php'; // Include the backend processes file

// Check if user is logged in
check_login();

// Fetch user details from the database
$userDetails = fetchUserDetails($_SESSION['user_id']);

// Check for contact seller success or error
$contactSuccess = isset($_GET['contact_success']) ? true : false;
$contactError = isset($_GET['contact_error']) ? true : false;

// Fetch user's listings
$userListings = fetchUserListings($_SESSION['user_id']);

// Fetch user's saved favorites
$savedFavorites = fetchSavedFavorites($_SESSION['user_id']);

// Check if profile update was successful
$updateSuccess = isset($_GET['update_success']) ? $_GET['update_success'] : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="text-center">Welcome to Your Dashboard, <?php echo $userDetails['username']; ?>!</h1>
        
        <?php if ($updateSuccess): ?>
            <div class="alert alert-success" role="alert">
                Profile updated successfully!
            </div>
        <?php endif; ?>

        <div class="row mt-4">
            <div class="col-md-6">
                <h2>Manage Listings</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userListings as $listing): ?>
                            <tr>
                                <td><?php echo $listing['title']; ?></td>
                                <td><?php echo $listing['location']; ?></td>
                                <td><?php echo $listing['price']; ?></td>
                                <td>
                                    <button class="btn btn-primary">Edit</button>
                                    <button class="btn btn-danger">Delete</button>
                                    <button class="btn btn-warning">Deactivate</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h2>Saved Favorites</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($savedFavorites as $favorite): ?>
                            <tr>
                                <td><?php echo $favorite['title']; ?></td>
                                <td><?php echo $favorite['location']; ?></td>
                                <td><?php echo $favorite['price']; ?></td>
                                <td>
                                    <button class="btn btn-danger">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add New Listing Form -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h2>Add New Listing</h2>
                <form action="../backend/create_listing.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="images">Upload Images</label>
                        <input type="file" class="form-control-file" id="images" name="images[]" accept="image/*" multiple required>
                        <small class="form-text text-muted">You can upload multiple images for the listing.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Listing</button>
                </form>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-md-6">
                <h2>Update Profile</h2>
                <form action="../backend/update_profile.php" method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $userDetails['email']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" class="form-control" id="age" name="age" value="<?php echo $userDetails['age']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option <?php if($userDetails['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option <?php if($userDetails['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            <option <?php if($userDetails['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Contact Seller</h2>
                <form action="../backend/contact_seller.php" method="post">
                    <div class="form-group">
                        <label for="listing_id">Listing ID</label>
                        <input type="text" class="form-control" id="listing_id" name="listing_id">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                <?php if ($contactSuccess): ?>
                    <div class="alert alert-success mt-3" role="alert">
                        Message sent successfully!
                    </div>
                <?php elseif ($contactError): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        Error sending message. Please try again.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
