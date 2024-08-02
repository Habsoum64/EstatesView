<?php
include '../backend/user_dashboard_backend.php'; // Include the backend processes file

// Check if user is logged in
check_login();

// Fetch user details from the database
$userDetails = fetchUserDetails($_SESSION['user_id']);

// Fetch user's listings
$userListings = fetchUserListings($_SESSION['user_id']);

// Fetch user's saved favorites
$savedFavorites = fetchSavedFavorites($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="user_dashboard.php">User Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Welcome to Your Dashboard, <?php echo $userDetails['username']; ?>!</h1>
        <div class="row mt-4">
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
                                <button class="btn btn-primary" onclick="editListingModal(<?php echo $listing['listing_id']; ?>)">Edit</button>
                                <button class="btn btn-danger" onclick="deleteListing(<?php echo $listing['listing_id']; ?>)">Delete</button>
                                <button class="btn btn-warning" onclick="deactivateListing(<?php echo $listing['listing_id']; ?>)">Deactivate</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <hr>

        <div class="row-mt-4">
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
                                <button class="btn btn-danger" onclick="removeFavorite(<?php echo $favorite['listing_id']; ?>)">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <hr>

        <!-- Add New Listing Form -->
        <div class="row mt-4">
            <div class="mx-auto">
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

        <!-- Edit Listing Modal -->
        <div class="modal fade" id="editListingModal" tabindex="-1" role="dialog" aria-labelledby="editListingModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editListingModalLabel">Edit Listing</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editListingForm">
                            <input type="hidden" id="editListingId" name="listing_id">
                            <div class="form-group">
                                <label for="editTitle">Title</label>
                                <input type="text" class="form-control" id="editTitle" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="editLocation">Location</label>
                                <input type="text" class="form-control" id="editLocation" name="location" required>
                            </div>
                            <div class="form-group">
                                <label for="editPrice">Price</label>
                                <input type="number" class="form-control" id="editPrice" name="price" required>
                            </div>
                            <div class="form-group">
                                <label for="editDescription">Description</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>

    </div>

    <script src="../lib/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editListingModal(listingId) {
            $.ajax({
                url: '../backend/user_dashboard_backend.php',
                method: 'POST',
                data: { action: 'getListing', listing_id: listingId },
                success: function(response) {
                    console.log('Response:', response); // Debugging line
                    try {
                        var listing = JSON.parse(response);
                        $('#editListingId').val(listing.listing_id);
                        $('#editTitle').val(listing.title);
                        $('#editLocation').val(listing.location);
                        $('#editPrice').val(listing.price);
                        $('#editDescription').val(listing.description);
                        $('#editListingModal').modal('show');
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Could not parse listing details. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching listing details: ', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Could not fetch listing details. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Submit Edit Listing Form
        $('#editListingForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '../backend/user_dashboard_backend.php',
                method: 'POST',
                data: {action: 'editListing', newData: formData},
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            title: 'Success',
                            text: 'Listing updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            location.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating listing: ', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Could not update listing. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Delete Listing
        function deleteListing(listingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../backend/user_dashboard_backend.php',
                        method: 'POST',
                        data: { action: 'deleteListing', listing_id: listingId },
                        success: function(response) {
                            if (response === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Listing has been deleted.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function() {
                                    location.reload();
                                });
                            } 
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting listing: ', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Could not delete listing. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        };

        // Deactivate Listing
        function deactivateListing(listingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to deactivate this listing?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, deactivate it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../backend/user_dashboard_backend.php',
                        method: 'POST',
                        data: { action: 'deactivateListing', listing_id: listingId },
                        success: function(response) {
                            if (response === 'success') {
                                Swal.fire({
                                    title: 'Deactivated!',
                                    text: 'Listing has been deactivated.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deactivating listing: ', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Could not deactivate listing. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        };

        // Remove Favorite
        function removeFavorite(listingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to remove this listing from your favorites?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../backend/user_dashboard_backend.php',
                        method: 'POST',
                        data: { action: 'removeFavorite', listing_id: listingId },
                        success: function(response) {
                            console.log('Response:', response); // Debugging line
                            if (response.trim() === 'success') {
                                Swal.fire({
                                    title: 'Removed!',
                                    text: 'Listing has been removed from your favorites.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Could not remove listing from favorites. Please try again later.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error removing favorite: ', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Could not remove listing from favorites. Please try again later.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>