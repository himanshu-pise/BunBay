<?php
session_start();
require('db_connection.php');

// Define an empty error message variable
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["vercode"] != $_SESSION["vercode"] || empty($_SESSION["vercode"])) {
        $error_message = "Incorrect verification code";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Hash the provided password using SHA256
        $hashed_password = hash('sha256', $password);

        // Prepare SQL query to retrieve hashed password from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // Verify hashed password
            if (hash_equals($row['password'], $hashed_password)) {
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit(); // Exit after redirect
            } else {
                $error_message = "Invalid username or password";
            }
        } else {
            $error_message = "Invalid username or password";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h2>Login</h2>
        <!-- Display error message in red color -->
        <?php if ($error_message) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="captcha">CAPTCHA: </label>
            <img src="captcha_image.php">
            <input type="text" class="form-control1"  name="vercode" maxlength="5" autocomplete="off" required  style="height:25px;" />&nbsp;
            
            <input type="submit" value="🔓 Login">
        </form>

        <!-- Signup button -->
        <p>Or <a href="signup.php">Signup</a> instead </p>
    </div>
<?php include('includes/footer.php'); ?> 
</body>
</html>
