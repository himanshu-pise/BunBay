<?php
session_start();
require('db_connection.php');


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Function to validate password
function validatePassword($password) {
    // Minimum 8 characters, contains at least one uppercase letter, one lowercase letter, and one number
    return preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password);
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["vercode"] != $_SESSION["vercode"] || empty($_SESSION["vercode"])) {
        $error_message = "Incorrect verification code";
    } else {
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];

        // Validate password
        if (!validatePassword($new_password)) {
            $error_message = "New password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
        }

        // Check if new password matches confirm password
        if ($new_password != $confirm_new_password) {
            $error_message = "New password and confirm password do not match.";
        }

        // If no error, proceed with updating password in the database
        if (!isset($error_message)) {
            // Hash the new password
            $hashed_new_password = hash('sha256', $new_password);

            // Update user's email and password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed_new_password, $username);

            if ($stmt->execute()) {
                $success_message = "Password updated successfully.";
            } else {
                $error_message = "Error: " . $conn->error;
            }

            // Close statement
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
    <style>
        /* Additional CSS for email input */
        input[type="email"] {
            height: 30px; /* Adjust the height as needed */
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?> <!-- Include your header -->
    <div class="container">
        <h2>Profile</h2>
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

        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <!-- Display the email fetched from the database -->
        <p>Email: <?php echo isset($email) ? $email : 'No Email Specified'; ?></p>
            <label for="new_password">New Password:</label>
            <p style="font-size: 12px; color: #999;">New password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</p>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_new_password">Confirm New Password:</label>
            <input type="password" id="confirm_new_password" name="confirm_new_password" required>
            <label for="captcha">CAPTCHA: </label>
            <img src="captcha_image.php">
            <input type="text" class="form-control1"  name="vercode" maxlength="5" autocomplete="off" required  style="height:25px;" />&nbsp;
            <input type="submit" value="Update Profile">
        </form>
        <p>Go back to <a href="dashboard.php">Dashboard</a></p>
    </div>
</body>
<?php include('includes/footer.php'); ?> 
</html>
