<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* General Body Styling */
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            font-family: Arial, sans-serif;
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            padding: 10px 0;
        }
        .navbar-brand {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Carousel Image Styling */
        .carousel-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            margin: auto;
            display: block;
            border-radius: 15px;
            border: 2px solid #ddd;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: gray;
        }
        .carousel-indicators .active {
            background-color: white;
        }

        /* Card Styling */
        .card {
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }

        /* Footer Styling */
        .footer {
            background: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .footer a {
            color: #ffcc00;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: white;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="images/logo.png" alt="Best Burgers Logo" width="150px" height="150px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Carousel -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://wallpapercave.com/wp/wp4062001.jpg" class="d-block w-50" alt="COJ 1">
        </div>
        <div class="carousel-item">
            <img src="https://img.freepik.com/premium-photo/french-fries_398492-8173.jpg" class="d-block w-50" alt="COJ 2">
        </div>
        <div class="carousel-item">
            <img src="https://th.bing.com/th/id/OIP.r9u3-0jGQsAUTaU55PN4FgHaE7?rs=1&pid=ImgDetMain" class="d-block w-50" alt="COJ 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- About Section -->
<section class="container mt-5">
    <h2>About Our Best Menu</h2>
    <p>Get ready to sink your teeth into mouthwatering burgers! Burgers are the epitome of comfort food, combining juicy patties, melty cheese, and an array of delicious toppings...</p>
</section>

<!-- Chef Section -->
<section class="container mt-5 text-center">
    <h2>Meet Our Chefs</h2>
    <p>Our talented chefs bring years of experience to craft the perfect burger for you.</p>
    <img src="images/chef.png" alt="Our Chefs" width="300px">
</section>

<!-- Our Goal Section -->
<div class="container mt-5 d-flex justify-content-center flex-wrap">
    <div class="card mx-2" style="width: 18rem;">
        <img src="images/goal1.jpg" class="card-img-top" alt="Our Goal">
        <div class="card-body">
            <h5 class="card-title">Quality Ingredients</h5>
            <p class="card-text">We only use the freshest and highest-quality ingredients.</p>
            <a href="#" class="btn btn-primary">Learn More</a>
        </div>
    </div>
    <div class="card mx-2" style="width: 18rem;">
        <img src="images/goal2.jpg" class="card-img-top" alt="Our Goal">
        <div class="card-body">
            <h5 class="card-title">Sustainable Sourcing</h5>
            <p class="card-text">We support sustainable and ethical food sourcing.</p>
            <a href="#" class="btn btn-primary">Learn More</a>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer mt-5">
    <p>Copyright &copy; <script>document.write(new Date().getFullYear())</script> Galleria - All Rights Reserved</p>
    <p>Connect with Us: 
        <a href="https://www.bing.com/ck/a?!&&p=997d2083caa4a819c742428673bab2ea3f337c64ee2c2f658f82be28a9f28e5dJmltdHM9MTc0NTYyNTYwMA&ptn=3&ver=2&hsh=4&fclid=23211a7b-49ce-63bb-2fe6-0f2948b16272&psq=facebook&u=a1aHR0cHM6Ly93d3cuZmFjZWJvb2suY29tLw&ntb=1">Facebook</a> | 
        <a href="">Twitter</a> | 
        <a href="https://www.instagram.com/accounts/login/">Instagram</a> | 
        <a href="#">LinkedIn</a>
    </p>
</div>

</body>
</html>
