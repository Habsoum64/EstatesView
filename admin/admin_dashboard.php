<?php
include '../settings/session.php';
include '../backend/listing_approval_backend.php'; // Include the listing approval backend file
include '../backend/user_management_backend.php'; // Include the user management backend file
include '../settings/connection.php'; // Include the database connection file

// Check if user is logged in
check_login();

// Check if the user is an admin
if ($_SESSION['user_type'] !== 'admin') {
    // Redirect to the user dashboard if the user is not an admin
    header("Location: user_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="../view/home.php">Estates View</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="../view/home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../view/out.php">About</a>
                    </li>
                    <!-- Add other navigation links as needed -->

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
        <h1>Welcome to the Admin Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
        
        <!-- Listing Approval Section -->
        <h2>Listing Approval</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Added By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listings as $listing): ?>
                    <tr>
                        <td><?php echo $listing['title']; ?></td>
                        <td><?php echo $listing['location']; ?></td>
                        <td><?php echo $listing['price']; ?></td>
                        <td><?php echo $listing['username']; ?></td>
                        <td>
                            <!-- Provide options for approving or rejecting the listing -->
                            <a href="admin_dashboard.php?action=approve&listing_id=<?php echo $listing['listing_id']; ?>" class="btn btn-success">Approve</a>
                            <a href="admin_dashboard.php?action=reject&listing_id=<?php echo $listing['listing_id']; ?>" class="btn btn-danger">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- User Management Section -->
        <h2>User Management</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['age']; ?></td>
                        <td><?php echo $user['gender']; ?></td>
                        <td><?php echo $user['user_type']; ?></td>
                        <td>
                            <!-- Provide options for managing user accounts -->
                            <a href="admin_dashboard.php?action=delete&user_id=<?php echo $user['user_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
