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

// Initialize message array
$message = [];
if (isset($_GET['logout'])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    
    // Redirect to the appropriate login page
    header("Location: admindashboard.php");
    exit();
}

if (isset($_POST['edit_dessert'])) {
    $dessert_id = intval($_POST['dessert_id']);
    $dessert_name = $conn->real_escape_string($_POST['dessert_name']);
    $dessert_price = $conn->real_escape_string($_POST['dessert_price']);
    
    $dessert_image = $_FILES['dessert_image']['name'];
    $dessert_image_tmp_name = $_FILES['dessert_image']['tmp_name'];
    $dessert_image_folder = 'images/' . time() . '_' . $dessert_image;

    if (!empty($dessert_image) && move_uploaded_file($dessert_image_tmp_name, $dessert_image_folder)) {
        $stmt = $conn->prepare("UPDATE `products` SET name = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $dessert_name, $dessert_price, $dessert_image_folder, $dessert_id);
    } else {
        $stmt = $conn->prepare("UPDATE `products` SET name = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssi", $dessert_name, $dessert_price, $dessert_id);
    }

    if ($stmt->execute()) {
        $message[] = 'Dessert updated successfully!';
    } else {
        $message[] = 'Error updating dessert: ' . $conn->error;
    }

    $stmt->close();
}

if (isset($_POST['add_dessert'])) {
    $dessert_name = $conn->real_escape_string($_POST['dessert_name']);
    $dessert_price = $conn->real_escape_string($_POST['dessert_price']);

    $dessert_image = $_FILES['dessert_image']['name'];
    $dessert_image_tmp_name = $_FILES['dessert_image']['tmp_name'];
    $dessert_image_folder = 'images/' . time() . '_' . $dessert_image;

    if (!empty($dessert_image) && move_uploaded_file($dessert_image_tmp_name, $dessert_image_folder)) {
        $stmt = $conn->prepare("INSERT INTO `products`(name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $dessert_name, $dessert_price, $dessert_image_folder);
        if ($stmt->execute()) {
            $message[] = 'Dessert added successfully!';
        } else {
            $message[] = 'Database insertion error: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $message[] = 'Failed to upload image.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Dessert</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
             background: linear-gradient(to right, #4b2c20, #8d6e63);
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        /* Heading Styles */
        h1 {
            text-align: center;
            color: #ff6f61;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 400px;
            background-color:#ECB176
        }

        form input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #ff6f61;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #e65c54;
        }

        /* Dessert Display */
        .desserts .heading {
            font-size: 24px;
            color: #ff6f61;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;


        }

        .box {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            background-color: #ECB176;
        }

        .box img {
    border-radius: 10px;
    width: 100%; /* Ensures the image fits within its container */
    height: 200px; /* Fixed height */
    object-fit: cover; /* Maintains aspect ratio and fills the defined dimensions */
}


        .btn {
            background-color: #ff6f61;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #e65c54;
        }

        /* Messages */
        .message {
            text-align: center;
            padding: 10px;
            background-color: #ffd4d4;
            color: #933;
            border-radius: 5px;
            margin: 10px auto;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <!-- Display Messages -->
    <?php if (!empty($message)) : ?>
        <?php foreach ($message as $msg) : ?>
            <div class="message"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Navigation Buttons -->
 <div class="flex" style="text-align:center; margin: 20px;">
    <a href="admindashboard.php" class="btn" style="margin-right:10px;">Admin Dashboard</a>
    <a href="index.php?logout=true" onclick="return confirm('Are you sure you want to logout?');" class="btn" style="background-color:#dc3545;">Logout</a>
</div>

    <!-- Add Dessert Form -->
    <h1>Add a Dessert</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="dessert_name" placeholder="Dessert Name" required>
        <input type="number" name="dessert_price" placeholder="Dessert Price" required>
        <input type="file" name="dessert_image" accept="image/*" required>
        <input type="submit" name="add_dessert" value="Add Dessert">
    </form>

    <!-- Display Latest Desserts -->
    <div class="desserts">
        <h1 class="heading">Our Latest Burgers</h1>
        <div class="box-container">
            <?php
            $select_dessert = $conn->query("SELECT * FROM `products`");
            if ($select_dessert->num_rows > 0) {
                while ($fetch_dessert = $select_dessert->fetch_assoc()) {
            ?>
                    <form method="post" enctype="multipart/form-data" class="box">
                        <img src="<?php echo htmlspecialchars($fetch_dessert['image']); ?>" alt="Dessert Image">
                        <div class="name"><?php echo htmlspecialchars($fetch_dessert['name']); ?></div>
                        <div class="price">₱<?php echo number_format($fetch_dessert['price'], 2); ?>/-</div>
                        <input type="hidden" name="dessert_id" value="<?php echo htmlspecialchars($fetch_dessert['id']); ?>">
                        <input type="text" name="dessert_name" value="<?php echo htmlspecialchars($fetch_dessert['name']); ?>" required>
                        <input type="number" name="dessert_price" value="<?php echo htmlspecialchars($fetch_dessert['price']); ?>" required>
                        <input type="file" name="dessert_image" accept="image/*">
                        <input type="submit" value="Edit" name="edit_dessert" class="btn">
                    </form>
            <?php
                }
            } else {
                echo '<p class="message">No desserts available right now. Please check back later!</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
