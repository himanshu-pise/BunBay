<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Dashboard</title>
</head>
<body>
    <?php include('includes/header.php'); ?> 
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <p>Here are some actions you can take:</p>
        <ul class="dashboard-options">
        <li><a href="all_listings.php">🔍 Browse Catalog</a></li>
            <li><a href="cart.php">🛒 View Cart</a></li>
            <li><a href="my_listings.php">📦 My Listings</a></li>
            <li><a href="profile.php">👤 Update Profile</a></li>
            <li><a href="feedback.php">💬 Give Feedback</a></li>
        </ul> 
        <form action="" method="post">
            <input type="submit" name="logout" value="🔒 Logout">
        </form>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>
