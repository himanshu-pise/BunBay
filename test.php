<?php
session_start();
require('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch user's service listings from the database
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM listings WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);

// Check if user has any service listings
if (empty($services)) {
    $no_listings_message = "You don't have any service listings yet. Click the button below to add a new listing.";
}

// Close statement
$stmt->close();

// Process delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_service"])) {
    $service_id = $_POST["service_id"];
    // Perform deletion
    $stmt = $conn->prepare("DELETE FROM listings WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    if ($stmt->execute()) {
        $delete_success_message = "Listing deleted successfully.";
    } else {
        $delete_error_message = "Error deleting listing.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this listing?");
        }
    </script>
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main content -->
    <div class="listings-container">
        <h2>Your Services</h2>
        <?php if (isset($no_listings_message)) : ?>
            <p><?php echo $no_listings_message; ?></p>
        <?php else : ?>
            <!-- Display user's service listings here -->
            <?php if (isset($delete_success_message)) : ?>
                <p style="color: green;"><?php echo $delete_success_message; ?></p>
            <?php elseif (isset($delete_error_message)) : ?>
                <p style="color: red;"><?php echo $delete_error_message; ?></p>
            <?php endif; ?>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($services as $service) : ?>
                    <tr>
                        <td><img src="<?php echo $service['location']; ?>" alt="Service Image" style="width: 100px;"></td>
                        <td><b><?php echo $service['title']; ?></b></td>
                        <td>
                        <p><?php echo $service['description']; ?></p>
                        </td>

                        <td><?php echo $service['price']; ?></td>
                        <td><?php echo $service['category']; ?></td>
                        <td><?php echo $service['location']; ?></td>
                        <td>
                            <form action="update_service.php" method="POST">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <input type="submit" name="update_service" value="Update">
                            </form>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return confirmDelete();">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <input type="submit" name="delete_service" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <button onclick="window.location.href='add_listing.php'">Add New Listing</button>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>
</body>
</html>
