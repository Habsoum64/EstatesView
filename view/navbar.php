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
                <!-- Conditional behavior based on user status -->
                <?php if (isset($_SESSION['user_id'])) { // If user is logged in ?>
                    <li class="nav-item">
                    <?php if ($_SESSION['user_type'] == 'Admin') { // If user is an admin ?>
                        <a class="nav-link" href="../admin/admin_dashboard.php">Admin Dashboard</a>
                    <?php } else { // If user is not an admin ?>
                        <a class="nav-link" href="user_dashboard.php">User Dashboard</a>
                    <?php } ?>
                    </li> 
                    <li>
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