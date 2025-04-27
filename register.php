<?php
$servername = "localhost";
$username = "root"; // Change based on your database setup
$password = ""; // Database password
$dbname = "yummyburger_db";

// Establish Database Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm-password"]);

    // Validate input fields
    if (empty($username) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required!'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if username already exists
    $check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_user->bind_param("s", $username);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        echo "<script>alert('Username already taken!'); window.location.href='register.php';</script>";
        exit();
    }

    $check_user->close();

    // ⚠️ Password is stored in plain text! This is NOT secure.
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register!'); window.location.href='register.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yummy Burger Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap");
    body {
      font-family: "Montserrat", sans-serif;
      margin: 0;
      padding: 0;
      background-color: #C14600;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
  </style>
</head>
<body>

  <!-- REGISTRATION FORM CONTAINER -->
  <div class="max-w-sm w-full bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-[#f9855f] mb-4 text-center">Create an Account</h2>
    <p class="text-sm text-gray-500 mb-6 text-center">Sign up to get started</p>

    <form action="register.php" method="POST">
      <!-- Username Input -->
      <div class="mb-4">
        <label for="username" class="block text-gray-600 text-sm font-medium">Username</label>
        <input type="text" id="username" name="username" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f9855f] focus:outline-none" placeholder="Enter your username" required>
      </div>

      <!-- Password Input -->
      <div class="mb-4">
        <label for="password" class="block text-gray-600 text-sm font-medium">Password</label>
        <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f9855f] focus:outline-none" placeholder="Create a password" required>
      </div>

      <!-- Confirm Password Input -->
      <div class="mb-4">
        <label for="confirm-password" class="block text-gray-600 text-sm font-medium">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#f9855f] focus:outline-none" placeholder="Confirm your password" required>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full bg-[#f9855f] text-white font-semibold text-lg py-3 rounded-lg hover:bg-orange-600 transition">Sign Up</button>
    </form>

    <!-- Login Link -->
    <p class="text-center text-sm text-gray-500 mt-6">
      Already have an account? <a href="index.php" class="text-[#f9855f] hover:underline">Login here</a>
    </p>
  </div>

</body>
</html>
