<?php
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yummyburger_db";

// Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);
if (isset($_SESSION['message'])) {
    echo '<div class="message">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']); // Clear the message after displaying
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Logout logic
if (isset($_GET['logout'])) {
    $_SESSION['message'] = "You have successfully logged out."; // Set logout message
    session_unset(); // Clear session variables
    session_destroy(); // Destroy the session
    header("Location: index.php");
    exit();
}

// Retrieve the username from the session
$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sliding Menu Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono&amp;family=Pacifico&amp;display=swap" rel="stylesheet"/>

    <style>
        body {
            background-image: url('https://media.giphy.com/media/pcIsZrDmlvFPG/giphy.gif');
            background-repeat: no-repeat;
            height: 100vh; 
            background-size: cover;
            background-position: center;
        }

        .offcanvas-body ul {
            padding: 20px 0;
        }

        .offcanvas-body ul li {
            margin-bottom: 15px;
            text-align: center;
        }

        .offcanvas-body ul li a {
            font-family: 'Arial', sans-serif;
            font-size: 22px;
            font-style: italic;
            text-transform: uppercase;
            padding: 12px 25px;
            display: inline-block;
            color: #FFC107;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .offcanvas-body ul li a:hover {
            color: #FFD700;
        }

        button.btn {
            width: 60px;
            height: 60px;
            font-size: 24px;
            padding: 10px;
        }

        .offcanvas-header h5 {
            font-family: 'Arial', sans-serif;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #FFC107;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);
        }

        .offcanvas-header img {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }

        h1 {
            position: absolute;
            bottom: 650px;
            right: 50px;
            width: 100%;
            text-align: center;
            font-family: 'Pacifico', cursive;
            font-size: 50px;
            letter-spacing: 2px;
            color: #F4DFC8;
            text-shadow: 4px 4px 6px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        h2 {
            position: absolute;
            bottom: 750px;
            right: 50px;
            width: 100%;
            text-align: center;
            font-family: 'Montserrat';
            font-size: 50px;
            letter-spacing: 2px;
            color: #F4DFC8;
            text-shadow: 4px 4px 6px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .offcanvas-header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px }

        .offcanvas-header img {
            width: 250px;
            height: 250px;
            border-radius: 40%;
            object-fit: cover;
        }

        .offcanvas-header h5 {
            font-size: 28px;
            font-weight: bold;
            color: #FFC107;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);
            margin-left: 15px;
        }

        .offcanvas-body ul {
            padding: 20px 0;
        }

        .offcanvas-body ul li a {
            font-size: 22px;
            text-transform: uppercase;
            color: #FFC107;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .offcanvas-body ul li a:hover {
            color: #FFD700;
        }
    </style>
</head>
<body>
    <h2>THE ULTIMATE</h2>
    <h1>Yummy Burger</h1>

    <!-- Burger menu button -->
    <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
        ☰
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header bg-dark text-white">
            <h5 id="offcanvasExampleLabel">
                <img src="images/logo 1.png" alt="Burger Logo" width="300px" height="300px">
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body bg-dark text-white">
            <ul class="list-unstyled">
          
                <li><a href="products.php">Menu</a></li>
                <li><a href="contact_dashboard.php">Contact Us</a></li>
                <li><a href="index.php" onclick="confirmLogout()">Logout</a></li>
            </ul>
        </div>
    </div>

<script>
    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "index.php?logout=true"; // Redirect to logout
        }
    }
</script>
    <!-- Rotating Image -->
</body>
</html>