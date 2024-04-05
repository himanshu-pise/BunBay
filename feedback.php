<?php
session_start();
require('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Default username and email of the logged-in user
$username = $_SESSION['username'];
$email = $_SESSION['email'];

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $feedback_text = htmlspecialchars($_POST['feedback_text']);
    $service_rating = intval($_POST['service_rating']); // Ensure integer value

    // Insert feedback into the database (using prepared statement)
    $stmt = $conn->prepare("INSERT INTO feedback (username, email, feedback_text, service_rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $feedback_text, $service_rating);

    if ($stmt->execute()) {
        $success_message = "Feedback submitted successfully.";
    } else {
        $error_message = "Error: Unable to submit feedback.";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body>
    <?php include('includes/header.php'); ?> <!-- Include your header -->

    <div class="container">
        <h2>Feedback</h2>
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <p>Hey, <?php echo $_SESSION['username']; ?>!</p>
            <p style="font-size: 12px; color: #999;">Devs take your feedback seriously. Let them know what you think.</p>
            <!-- Display the email fetched from the database -->
            <label for="feedback_text">Feedback:</label>
            <textarea id="feedback_text" name="feedback_text" rows="7" cols="50" required><?php echo isset($feedback_text) ? $feedback_text : ''; ?></textarea>
            <label for="service_rating">Service Rating (out of 5 stars):</label><br>
            <select id="service_rating" name="service_rating" required>
            <option value="5">5★</option>
            <option value="4">4★</option>
            <option value="3">3★</option>
            <option value="2">2★</option>
            <option value="1">1★</option>
            </select>
            <input type="submit" value="Submit Feedback">
        </form>
        <p> Or contact the developer on <a href= "https://in.linkedin.com/in/himanshupise"> LinkedIn</a></p>
        <p>Go back to <a href="dashboard.php">Dashboard</a></p>
    </div>
    
    <?php include('includes/footer.php'); ?> <!-- Include your footer -->
</body>
</html>
