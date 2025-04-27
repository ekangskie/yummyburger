<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yummyburger_db";

// Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger Heaven</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 20px;
            background: linear-gradient(to right, #4b2c20, #8d6e63); /* Beautiful gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            min-height: 100vh;
        }

        .container-wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .container {
            max-width: 380px;
            background:#ECB176;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-radius: 12px; /* Rounded corners */
            text-align: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .container:hover {
            transform: scale(1.05); /* Slight zoom effect */
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
        }

        .burger-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover .burger-img {
            transform: rotate(3deg); /* Subtle tilt */
        }

        .price {
            font-size: 22px;
            color: #e63946;
            font-weight: bold;
        }

        .order-btn {
            display: inline-block;
            padding: 12px 20px;
            background: #e63946;
            color: white;
            font-size: 18px;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .order-btn:hover {
            background: #c92a3d;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="container">
            <h1>Burger</h1>
            <img src="images/4.png" alt="Burgers" class="burger-img">
            <p>Experience the juiciest, most flavorful burger with fresh ingredients and a toasted bun.</p>
            <p class="price">₱5.99</p>
            <a href="burger_admin.php" class="order-btn">Order Now</a>
        </div>

        <div class="container">
            <h1>Drinks</h1>
            <img src="images/3.png" alt="Drinks" class="burger-img">
            <p>A delicious, healthy alternative packed with fresh vegetables and a crispy bun.</p>
            <p class="price">₱5.99</p>
            <a href="drinks_admin.php" class="order-btn">Order Now</a>
        </div>

        <div class="container">
            <h1>Fries</h1>
            <img src="images/fries.png" alt="Fries" class="burger-img">
            <p>A fiery delight for spice lovers, topped with jalapeños and spicy sauce!</p>
            <p class="price">₱6.99</p>
            <a href="fries_admin.php" class="order-btn">Order Now</a>
        </div>
    </div>
</body>
</html>
