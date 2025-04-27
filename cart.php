<?php
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yummyburger_db";

// Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user profile to get user_id
$username = $_SESSION['username'];
$user_query = $conn->query("SELECT id FROM users WHERE username = '$username'");
if ($user_query->num_rows > 0) {
    $user_data = $user_query->fetch_assoc();
    $user_id = intval($user_data['id']);
} else {
    die("User not found!");
}

// Retrieve cart items for the logged-in user using user_id
$cart_items_query = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");

// Remove an item from the cart
if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];
    $conn->query("DELETE FROM cart WHERE id = '$item_id'");
    header("Location: cart.php"); // Refresh cart
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
             background: linear-gradient(to right, #4b2c20, #8d6e63);
        }

        .cart-container {
            width: 90%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart-item-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .cart-item-info h2 {
            font-size: 1.2rem;
            margin: 0;
        }

        .cart-item-info p {
            margin: 5px 0;
        }

        .btn-remove {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-remove:hover {
            background-color: #c82333;
        }

        .checkout-btn {
            display: block;
            text-align: center;
            background-color: #28a745;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        .empty-cart {
            text-align: center;
            font-size: 1.2rem;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1>Your Cart</h1>
        <?php if ($cart_items_query->num_rows > 0): ?>
            <?php while ($item = $cart_items_query->fetch_assoc()): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image">
                    <div class="cart-item-info">
                        <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p>Price: ₱<?php echo number_format($item['price'], 2); ?></p>
                        <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                    </div>
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="remove_item" class="btn-remove">Remove</button>
                    </form>
                </div>
            <?php endwhile; ?>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php else: ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
