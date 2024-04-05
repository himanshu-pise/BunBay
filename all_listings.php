<?php
session_start();
require('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch logged-in user's username and email
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Fetch available products not listed by the logged-in user
$stmt = $conn->prepare("SELECT * FROM listings WHERE sold = 0 AND username != ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main content -->
    <div class="listings-container">
        <h2>Available Products</h2>
        <?php if ($result->num_rows > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Seller</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo '$' . htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['location']); ?>" alt="Product Image" style="width: 100px;"></td>
                            <td>
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" name="add_to_cart" value="Add to Cart">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <h3>All done? Proceed ahead to your <a href="cart.php">  🛒  </a> to check out.</h3>
        <?php else : ?>
            <p>No listings available.</p>
        <?php endif; ?>
        <p>Go back to <a href="dashboard.php">Dashboard</a></p>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>
</body>
</html>
