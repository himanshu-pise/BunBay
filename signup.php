<?php
session_start();
require('db_connection.php');



// Function to validate password
function validatePassword($password) {
    // Minimum 8 characters, contains at least one uppercase letter, one lowercase letter, and one number
    return preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password);
}


// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["vercode"] != $_SESSION["vercode"] || empty($_SESSION["vercode"])) {
        $error_message = "Incorrect verification code";
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate password
        if (!validatePassword($password)) {
            $error_message = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
        }

        // Validate email
        if (!validateEmail($email)) {
            $error_message = "Invalid email address.";
        }

        // Check if password matches confirm password
        if ($password != $confirm_password) {
            $error_message = "Passwords do not match.";
        }

        function sanitize_input($input) {
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        // Check if username or email already exists in the database
        $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error_message = "Username or email already exists.";
        }

        // If no error, proceed with database insertion
        if (!isset($error_message)) {
            // Hash the password
            $hashed_password = hash('sha256', $password);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success_message = "Signup successful. You can now login.";
            } else {
                $error_message = "Error: " . $conn->error;
            }

            // Close statement
            $stmt->close();
        }
    }
    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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
        <h2>Signup</h2>
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
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <p style="font-size: 12px; color: #999;">New password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</p>
            <input type="password" id="password" name="password" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <label for="captcha">CAPTCHA: </label>
            <img src="captcha_image.php">
            <input type="text" class="form-control1"  name="vercode" maxlength="5" autocomplete="off" required  style="height:25px;" />&nbsp;
            <input type="submit" value="Signup">
        </form>
        <p>Have an account? <a href="login.php">Login</a> instead </p>
    </div>
</body>
<?php include('includes/footer.php'); ?> 
</html>
