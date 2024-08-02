<?php
include '../settings/session.php';
include '../backend/listing_approval_backend.php'; // Include the listing approval backend file
include '../backend/user_management.php'; // Include the user management backend file
include '../settings/connection.php'; // Include the database connection file

// Check if user is logged in
check_login();

// Check if the user is an admin
if ($_SESSION['user_type'] !== 'Admin') {
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
    <link rel="stylesheet" href="../lib/sweetalert2/sweetalert2.css">
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
                    <li class="nav-item">
                        <a class="nav-link" href="../view/home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/admin_dashboard.php">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login/logout.php">Logout</a>
                    </li>
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
                    <th>Created at</th>
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
                        <td><?php echo $listing['created_at']; ?></td>
                        <td><?php echo $listing['location']; ?></td>
                        <td><?php echo $listing['price']; ?></td>
                        <td><?php echo $listing['user_id']; ?></td>
                        <td>
                            <!-- Provide options for approving or rejecting the listing -->
                            <a onclick="updateListingStatus('approve', <?php echo $listing['listing_id']; ?>)" class="btn btn-success">Approve</a>
                            <a onclick="updateListingStatus('reject', <?php echo $listing['listing_id']; ?>)" class="btn btn-danger">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <hr>

        <!-- User Management Section -->
        <h2>User Management</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateListingStatus(status_change, listingId) {
            let action = '';
            let message = '';

            if (status_change === 'approve') {
                action = 'approve';
                message = 'Do you really want to approve this listing?';
            } else if (status_change === 'reject') {
                action = 'reject';
                message = 'Do you really want to reject this listing?';
            }

            Swal.fire({
                title: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes, ' + status_change,
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // User clicked 'Yes'
                    $.ajax({
                        url: '../backend/listing_approval_backend.php',
                        method: 'GET',
                        data: {
                            action: action,
                            listing_id: listingId
                        },
                        success: function (response) {
                            if (response.trim() === 'success') {
                                Swal.fire({
                                    title: "Status changed!",
                                    icon: "success",
                                    confirmButtonClass: "btn btn-success",
                                    buttonsStyling: false
                                }).then(() => {
                                    location.reload(); // Reload page to update the listing status
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: "Unable to change status.",
                                    icon: "error",
                                    confirmButtonClass: "btn btn-danger",
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error changing listing status: ', error);
                            Swal.fire({
                                title: "Error",
                                text: "Failed to update the listing's status.",
                                icon: "error",
                                confirmButtonClass: "btn btn-danger",
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this user account? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-secondary',
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../backend/user_management.php',
                        method: 'POST',
                        data: { action: 'deleteUser', user_id: userId },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'User account has been deleted.',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-success',
                                    buttonsStyling: false
                                }).then(function() {
                                    location.reload(); // Reload page to reflect changes
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Could not delete user account. Please try again later.',
                                    icon: 'error',
                                    confirmButtonClass: 'btn btn-danger',
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting user account: ', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to delete user account. Please try again later.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-danger',
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
