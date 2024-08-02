
<?php if ($updateSuccess): ?>
    <div class="alert alert-success" role="alert">
        Profile updated successfully!
    </div>
<?php endif; ?>

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