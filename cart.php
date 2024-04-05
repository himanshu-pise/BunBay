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

// Initialize cart array if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Process adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    // Add product to cart if not already in cart
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

// Process removal from cart if submitted via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_product"])) {
    $remove_product_id = $_POST["remove_product"];
    // Find and remove product from cart array
    $index = array_search($remove_product_id, $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
    }
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit;
}

// Fetch products in the cart with category and seller name
$cart_products = array();
if (!empty($_SESSION['cart'])) {
    $cart_product_ids = implode(',', $_SESSION['cart']);
    $stmt = $conn->prepare("SELECT l.*, u.username AS seller_name FROM listings l INNER JOIN users u ON l.username = u.username WHERE l.id IN ($cart_product_ids)");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_products[] = $row;
    }
    $stmt->close();
}

// Calculate total price of products in the cart
$total_price = 0;
foreach ($cart_products as $product) {
    $total_price += $product['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main content -->
    <div class="container">
        <h2>🛒 Cart</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th>Category</th>
                <th>Seller</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cart_products as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['title']); ?></td>
                    <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><?php echo htmlspecialchars($product['seller_name']); ?></td>
                    <td>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="remove_product" value="<?php echo $product['id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>$<?php echo $total_price; ?></strong></td>
                <td></td>
                <td></td>
                <td>
                    <form action="buy.php" method="POST">
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        <button type="submit" name="buy_now" style="background-color: blue; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Buy Now</button>
                    </form>
                </td>
            </tr>
        </table>
        <p>Browse <a href="all_listings.php">here</a> to check out more stuff</p>
        <p>Or else go back to <a href="dashboard.php">Dashboard</a></p>
    </div>
    <?php 
    $_SESSION['total_price'] = $total_price;
    ?>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>
</body>
</html>
