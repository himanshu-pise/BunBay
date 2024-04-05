<?php
session_start();
require('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Function to sanitize user input
function sanitize_input($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_service"])) {
    // Fetch service details
    $service_id = sanitize_input($_POST["service_id"]);
    $stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();

    // Display form to update service details
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Service</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <!-- Header -->
        <?php include('includes/header.php'); ?>

        <!-- Main content -->
        <div class="container">
            <h2>Update Service</h2>
            <form action="" method="POST">
                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo sanitize_input($service['title']); ?>" required><br><br>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo sanitize_input($service['description']); ?></textarea><br><br>
                
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo sanitize_input($service['price']); ?>" required><br><br>
                
                <label for="category">Category:</label><select id="category" name="category" required>
                <option value="Product">Product</option>
                <option value="Service">Service</option>
            </select><br><br>
                
                <input type="submit" name="update_submit" value="Update">
            </form>
        </div>

        <!-- Footer -->
        <?php include('includes/footer.php'); ?>
    </body>
    </html>
    <?php
}

// Process update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_submit"])) {
    // Validate and sanitize input
    $service_id = sanitize_input($_POST["service_id"]);
    $title = sanitize_input($_POST["title"]);
    $description = sanitize_input($_POST["description"]);
    $price = sanitize_input($_POST["price"]);
    $category = sanitize_input($_POST["category"]);

    // Update service details in the database
    $stmt = $conn->prepare("UPDATE listings SET title = ?, description = ?, price = ?, category = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $title, $description, $price, $category, $service_id);
    if ($stmt->execute()) {
        header("Location: my_listings.php"); // Redirect to my_listings.php after successful update
        exit;
    } else {
        // Error updating service
        echo "Error updating service.";
    }
    $stmt->close();
}
?>
