<?php
session_start();
require('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if total price is set in session
if (!isset($_SESSION['total_price'])) {
    // Redirect back to cart.php if total price is not set
    header("Location: cart.php");
    exit;
}

// Initialize total price
$total_price = $_SESSION['total_price'];

// Initialize buyer details
$buyer_username = $_SESSION['username'];

// Fetch buyer email from the database
$stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
$stmt->bind_param("s", $buyer_username);

if ($stmt->execute()) {
    $stmt->bind_result($buyer_email);
    $stmt->fetch();
    $stmt->close();
} else {
    // Error in executing the query
    echo "Error: Unable to fetch email from the database.";
    exit;
}

// Process payment form submission if "buy_now" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    // Validate and sanitize input
    $card_number = htmlspecialchars($_POST["card_number"], ENT_QUOTES, 'UTF-8');
    $cvv = htmlspecialchars($_POST["cvv"], ENT_QUOTES, 'UTF-8');
    $expiry_date = htmlspecialchars($_POST["expiry_date"], ENT_QUOTES, 'UTF-8');

    // Concatenate card details
    $card = $cvv . '/' . $card_number . '/' . $expiry_date;

    // Create SHA256 hash string
    $hashed_card = hash('sha256', $card);

    // Insert buy details into the database
    $purchase_id = uniqid(); // Generate unique purchase ID

    // Prepare and execute statement to update listings table for each product in the cart
    $stmt = $conn->prepare("UPDATE listings SET buyer_username = ?, buyer_email = ?, sold = 1, purchase_id = ?, card_details = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $buyer_username, $buyer_email, $purchase_id, $hashed_card, $product_id);

    foreach ($_SESSION['cart'] as $product_id) {
        $stmt->execute();
    }

    // Close statement
    $stmt->close();

    // Clear the cart
    $_SESSION['cart'] = array();

    // Redirect to dashboard.php after processing payment
    //header("Location: dashboard.php");
    //exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main content -->
    <div class="container">
        <h2>Buy Now</h2>
        <p>Total Amount: $<?php echo $total_price; ?></p>
        <form action="" method="POST">
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" maxlength="16" required><br><br>
            
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" maxlength="3" required><br><br>
            
            <label for="expiry_date">Expiry Date (MM/YYYY):</label>
            <input type="text" id="expiry_date" name="expiry_date" maxlength="7" placeholder="MM/YYYY" required><br><br>
            
            <input type="submit" name="Confirm Payment" value="Confirm Payment">
        </form>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>
</body>
</html>
