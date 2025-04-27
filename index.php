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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    // Check if admin credentials exist in the `admins` table
    $admin_stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $admin_stmt->bind_param("ss", $user, $pass);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();

    if ($admin_result->num_rows == 1) {
        $_SESSION["username"] = $user; // Store admin session
        $_SESSION["message"] = "Welcome, Admin!";
        header("Location: admindashboard.php"); // Redirect to admin dashboard
        exit();
    }

    // Check if username contains numbers
  if (preg_match('/[0-9]/', $user)) {
    $_SESSION["message"] = "Username cannot contain numbers. Please enter a valid username.";
} else {
    // Query to check if username exists in `users` table
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Compare plain-text passwords
        if ($pass === $row["password"]) {
            $_SESSION["username"] = $user;
         // Assuming 'id' is the user number
         
            header("Location: maindashboard.php");
            exit();
        } else {
            $_SESSION["message"] = "Invalid password.";
        }
    } else {
        $_SESSION["message"] = "User not found.";
    }

    $stmt->close();
}

$admin_stmt->close();

$conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yummy Burger Login</title>
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
    .error-message {
      background-color: #C14600;
      color: white;
      padding: 12px;
      text-align: center;
      font-weight: bold;
      border-radius: 5px;
      max-width: 400px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <!-- LOGIN FORM CONTAINER -->
  <div class="max-w-sm w-full bg-white p-8 rounded-lg shadow-lg">
    
    <!-- ERROR MESSAGE -->
    <?php if (!empty($error_message)): ?>
      <div class="error-message">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>

    <h2 class="text-2xl font-bold text-[#143421] mb-4 text-center">Welcome Back</h2>
    <p class="text-sm text-gray-500 mb-6 text-center">Sign in to continue</p>

    <form action="index.php" method="POST">
      <!-- Username Input -->
      <div class="mb-4">
        <label for="username" class="block text-gray-600 text-sm font-medium">Username</label>
        <input type="text" id="username" name="username" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C14600] focus:outline-none" placeholder="Enter your username" required>
      </div>

      <!-- Password Input -->
      <div class="mb-4">
        <label for="password" class="block text-gray-600 text-sm font-medium">Password</label>
        <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C14600] focus:outline-none" placeholder="Enter your password" required>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full bg-[#C14600] text-white font-semibold text-lg py-3 rounded-lg hover:bg-orange-600 transition">Login</button>
    </form>

    <!-- Registration Link -->
    <p class="text-center text-sm text-gray-500 mt-6">
      Don't have an account? <a href="register.php" class="text-[#C14600] hover:underline">Sign up here</a>
    </p>
  </div>

</body>
</html>
