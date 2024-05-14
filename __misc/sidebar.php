<!DOCTYPE html>
<html lang="en">
<!-- sidebar.php -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>Dashboard</h3>
    </div>

    <ul class="list-unstyled components">
        <?php if (get_user_type() === 'Admin'): ?>
        <li>
            <a href="admin_dashboard.php">Admin Dashboard</a>
        </li>
        <?php else: ?>
        <li>
            <a href="user_dashboard.php">User Dashboard</a>
        </li>
        <?php endif; ?>
        <li>
            <a href="add_listing.php">Add New Listing</a>
        </li>
        <li>
            <a href="manage_listings.php">Manage Listings</a>
        </li>
        <li>
            <a href="saved_favorites.php">Saved Favorites</a>
        </li>
        <li>
            <a href="profile.php">Profile</a>
        </li>
        <li>
            <a href="logout.php">Logout</a>
        </li>
    </ul>
</nav>
</html>