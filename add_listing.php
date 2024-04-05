<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Default username and email of the logged-in user
$username = $_SESSION['username'];

// Fetch email from the database based on username
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
} else {
    // Error in executing the query
    echo "Error: Unable to fetch email from the database.";
    exit;
}

$_SESSION['buyer_email'] = $email;

// Define variables to hold form input
$title = $description = $price = $category = $location = "";
$error_message = $success_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Check if file is selected
    if (!empty($_FILES['image']['name'])) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        // Generate a unique name for the image
        $image = $image_name;
        $target_file = "/opt/lampp/htdocs/gpt_proj/img/" . $image;

        // Move uploaded file to img folder
        if (move_uploaded_file($image_tmp, $target_file)) {
            // Insert listing into database
            $location = "img/" . $image;
            $stmt = $conn->prepare("INSERT INTO listings (username, email, title, description, price, category, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssdss", $username, $email, $title, $description, $price, $category, $location);
            if ($stmt->execute()) {
                $success_message = "Listing added successfully.";
                $title = $description = $price = $category = $location = "";
            } else {
                $error_message = "Error adding listing: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error_message = "Please select an image.";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Listing</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body>
    <?php include('includes/header.php'); ?> <!-- Include your header -->

    <div class="container">
        <h2>Add New Listing</h2>
        <?php 
        // Display error message if exists
        if(isset($error_message)) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        // Display success message if exists
        if(isset($success_message)) {
            echo "<p style='color:green;'>$success_message</p>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $price; ?>" required>
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Product">Product</option>
                <option value="Service">Service</option>
            </select>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" required>
            <input type="submit" value="Add Listing">
        </form>
        <p>Go back to <a href="dashboard.php">Dashboard</a></p>
    </div>

    <?php include('includes/footer.php'); ?> <!-- Include your footer -->
</body>
</html>
