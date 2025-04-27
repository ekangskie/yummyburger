<?php
session_start();

if (isset($_SESSION['message'])) {
    echo '<div class="message">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']); // Clear the message after displaying
}
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

// Redirect if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch logged-in user's ID
$username = $_SESSION['username'];
$user_query = $conn->query("SELECT id FROM `users` WHERE username = '$username'");
if ($user_query->num_rows > 0) {
    $user_data = $user_query->fetch_assoc();
    $user_id = $user_data['id'];
} else {
    die("User not found!");
}

// Initialize message array
$message = [];

// Add Product Logic
if (isset($_POST['add_product'])) {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $product_price = $conn->real_escape_string($_POST['product_price']);

    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'images/' . time() . '_' . $product_image;

    if (!empty($product_image) && move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
        $stmt = $conn->prepare("INSERT INTO `products`(name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $product_name, $product_price, $product_image_folder);
        if ($stmt->execute()) {
            $message[] = 'Product added successfully!';
        } else {
            $message[] = 'Database insertion error: ' . $conn->error;
        }
        $stmt->close();
    } else {
        $message[] = 'Failed to upload image.';
    }
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $product_price = $conn->real_escape_string($_POST['product_price']);
    $product_image = $conn->real_escape_string($_POST['product_image']);
    $product_quantity = intval($_POST['product_quantity']);

    $cart_query = $conn->query("SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");
    
    if ($cart_query->num_rows > 0) {
        // If product exists in cart, update the quantity instead
        $row = $cart_query->fetch_assoc();
        $new_quantity = $row['quantity'] + $product_quantity;
        
        $update_stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE name = ? AND user_id = ?");
        $update_stmt->bind_param("isi", $new_quantity, $product_name, $user_id);
        
        if ($update_stmt->execute()) {
            $message[] = 'Product quantity updated!';
        } else {
            $message[] = 'Error updating cart: ' . $conn->error;
        }
        
        $update_stmt->close();
    } else {
        // If product is not in cart, insert it
        $stmt = $conn->prepare("INSERT INTO `cart` (user_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $user_id, $product_name, $product_price, $product_image, $product_quantity);
        
        if ($stmt->execute()) {
            $message[] = 'Product added to cart!';
        } else {
            $message[] = 'Error adding to cart: ' . $conn->error;
        }
        
        $stmt->close();
    }
}


// Update Cart Logic
if (isset($_POST['update_cart'])) {
    $update_quantity = intval($_POST['cart_quantity']);
    $update_id = intval($_POST['cart_id']);

    $stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $update_quantity, $update_id);
    if ($stmt->execute()) {
        $message[] = 'Cart updated!';
    } else {
        $message[] = 'Error updating cart: ' . $conn->error;
    }
    $stmt->close();
}

// Remove Item from Cart Logic
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $stmt->bind_param("i", $remove_id);
    if ($stmt->execute()) {
        // Add success message
        $_SESSION['message'] = 'Item successfully removed from the cart!';
    } else {
        // Add error message if deletion fails
        $_SESSION['message'] = 'Failed to remove item from the cart.';
    }
    $stmt->close();
    header('Location: burger_form.php');
    exit();
}


// Delete All from Cart Logic
if (isset($_GET['delete_all'])) {
    $stmt = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header('Location: burger_form.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Shopping Cart</title>
    <style>
        /* General Style */
      body {
    font-family: 'Roboto', sans-serif;
     background: linear-gradient(to right, #4b2c20, #8d6e63); /* Beautiful gradient *//
    background-attachment: fixed;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    color: #333;
}
        /* Message Styling */
.message {
    padding: 10px 20px;
    margin: 10px 0;
    background-color: #fffbcc; /* Light yellow background */
    color: #665700; /* Dark text for good contrast */
    border-left: 5px solid #ffcc00; /* Highlight with a golden border */
    font-family: Arial, sans-serif;
    font-size: 14px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
    cursor: pointer; /* Makes it clear that the message is clickable */
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.message:hover {
    background-color: #fff3bf; /* Slightly darker yellow on hover */
    transform: scale(1.02); /* Subtle zoom-in effect */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}


        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

     
h1.heading {
    text-align: center;
    font-size: 2.5rem;
    margin: 20px 0;
    font-weight: 700;
    color: #222;
}
        /* Buttons */
        .btn, .option-btn, .delete-btn {
            padding: 10px 15px;
            margin: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn:hover, .option-btn:hover, .delete-btn:hover {
            background-color: #0056b3;
        }

        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Product Section */
        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .box {
            border: 1px solid #ddd;
            padding: 15px;
            width: 250px;
            border-radius: 10px;
            text-align: center;
            background-color:#ECB176
        }
        .box:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

      .box img {
    width: 100px;
    height: 100px;
    border-radius: 10px;
}

        /* Shopping Cart Table */
      /* Shopping Cart Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
}
        thead {
            background-color: #007BFF;
            color: white;
        }
thead th {
    padding: 10px;
    text-align: center;
    background-color: #007BFF;
    color: white;
    font-weight: 500;
}
tbody td {
    padding: 10px;
    text-align: center;
}

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

      
.table-bottom td {
    font-weight: bold;
    text-align: center;
}
#round-img {
    width: 80px; /* Adjust the size as needed */
    height: 80px;
    border-radius: 50%; /* Makes it round */
    object-fit: cover; /* Ensures the image fits nicely */
}

     
    </style>
</head>
<body>
    <!-- Display Messages -->
    <?php if (!empty($message)) : ?>
        <?php foreach ($message as $msg) : ?>
            <div class="message" onclick="this.remove();"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="container">
        <!-- User Profile Section -->
        <div class="user-profile">
            <?php
            $select_user = $conn->query("SELECT * FROM `users` WHERE id = '$user_id'");
            if ($select_user->num_rows > 0) {
                $fetch_user = $select_user->fetch_assoc();
            }
            ?>
            <p>Username: <span><?php echo htmlspecialchars($fetch_user['username']); ?></span></p>
          <div class="flex">
   
    <a href="products.php" class="option-btn"><i class="fas fa-hamburger"></i></a>

    <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="delete-btn"><i class="fas fa-sign-out-alt"></i></a>
</div>

        </div>


        <!-- Products Section -->
        <div class="products">
            <h1 class="heading">Our Latest Burgers</h1>
            <div class="box-container">
                <?php
                $select_product = $conn->query("SELECT * FROM `products`");
                if ($select_product->num_rows > 0) {
                    while ($fetch_product = $select_product->fetch_assoc()) {
                ?>
                        <form method="post" class="box">
                            <img src="<?php echo htmlspecialchars($fetch_product['image']); ?>" alt="Product Image">
                            <div class="name"><?php echo htmlspecialchars($fetch_product['name']); ?></div>
                            <div class="price"> ₱<?php echo number_format($fetch_product['price'], 2); ?>/-</div>
                            <input type="number" min="1" name="product_quantity" value="1">
                            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_product['image']); ?>">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_product['name']); ?>">
                            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_product['price']); ?>">
                            <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
                              <input type="submit" name="update_cart" value="Update" class="option-btn">
                        </form>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <!-- Shopping Cart Section -->
        <div class="shopping-cart">
            <h1 class="heading">Shopping Cart</h1>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cart_query = $conn->query("SELECT * FROM `cart` WHERE user_id = '$user_id'");
                    $grand_total = 0;
                    if ($cart_query->num_rows > 0) {
                        while ($fetch_cart = $cart_query->fetch_assoc()) {
                            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                            $grand_total += $sub_total;
                    ?>
                            <tr>
                             <td><img id="round-img" src="<?php echo htmlspecialchars($fetch_cart['image']); ?>" alt="Product Image"></td>


                                <td><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
                                <td> ₱<?php echo number_format($fetch_cart['price'], 2); ?>/-</td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <input type=""  name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                                       
                                    </form>
                                </td>
                                <td> ₱<?php echo number_format($sub_total, 2); ?>/-</td>
                                <td><a href="burger_form.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('are u sure to remove this?');">Remove</a></td>
                            </tr>
                    <?php
                        }
                    } else {
                    }

            ?>
            <tr class="table-bottom">
         <td colspan="4">grand total :</td>
         <td>$<?php echo $grand_total; ?>/-</td>
         <td><a href="burger_form.php?delete_all" onclick="return confirm('delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">delete all</a></td>
      </tr>
   </tbody>
   </table>

   <div class="cart-btn">  
      <h3>Grand Total: <?php echo $grand_total; ?></h3>
      <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

</div>

</div>
<script>
        // Get the audio element
        var audio = document.getElementById('myAudio');

        // Play the audio when the page loads
        window.onload = function() {
            audio.play().catch(function(error) {
                console.log("Autoplay was prevented. User interaction is required.");
            });
        };
    </script>
</body>
</html>