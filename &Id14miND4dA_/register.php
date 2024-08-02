<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-dark text-light py-4">
        <div class="container">
            <h1 class="display-4">Register for Real Estate Listings</h1>
        </div>
    </header>

    <main class="container mt-4">
        <form action="register_process.php" method="POST" onsubmit="return validateForm()">
            <!-- Username field -->
            <div class="form-group">
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            </div>
            <!-- Email field -->
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
            </div>
            <!-- Password field -->
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            </div>
            <!-- Gender field -->
            <div class="form-group">
                <select name="gender" id="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <!-- Date of Birth field -->
            <div class="form-group">
                <input type="date" name="dob" id="dob" class="form-control" placeholder="Date of Birth" required>
            </div>
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Register</button>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>
        </form>
        <!-- Link to login page -->
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>

        <!-- Link to home page -->
        <p class="mt-3"><a href="../view/home.php">Return home</a></p>
    </main>

    <footer class="bg-dark text-light py-3 mt-5">
        <div class="container">
            <p>&copy; 2024 Real Estate Listings</p>
        </div>
    </footer>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Function to validate form -->
    <script>
    function validateForm() {
        var username = document.getElementById('username').value.trim();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();
        var gender = document.getElementById('gender').value.trim();
        var dob = document.getElementById('dob').value.trim();

        // Check if any field is empty
        if (username === '' || email === '' || password === '' || gender === '' || dob === '') {
            alert("Please fill in all fields.");
            return false;
        }

        // Validate email format
        if (!validateEmail(email)) {
            alert("Invalid email format.");
            return false;
        }

        // Validate password strength
        if (!validatePassword(password)) {
            alert("Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one digit, and one special character.");
            return false;
        }

        // If all validation passed, return true to submit the form
        return true;
    }

    function validateEmail(email) {
        // Regular expression for validating email format
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function validatePassword(password) {
        // Regular expression for validating password strength
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[a-zA-Z\d\W_]{8,}$/;
        return regex.test(password);
    }
    </script>
</body>
</html>
