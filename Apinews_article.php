<?php
session_start();
include('db_connection.php');

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

$unreadQuery = "SELECT COUNT(*) AS unread_count FROM contact WHERE read_status = 0";
$unreadResult = $conn->query($unreadQuery);
$unreadMessages = 0;

if ($unreadResult->num_rows > 0) {
    $row = $unreadResult->fetch_assoc();
    $unreadMessages = $row['unread_count'];
}

// Fetch unread comments count
$unreadCommentsQuery = "SELECT COUNT(*) AS unread_count FROM comments WHERE read_status = 0"; // Update the table name accordingly
$unreadCommentsResult = $conn->query($unreadCommentsQuery);
$unreadComments = 0;

if ($unreadCommentsResult->num_rows > 0) {
    $row = $unreadCommentsResult->fetch_assoc();
    $unreadComments = $row['unread_count'];
}

// Calculate total unread notifications
$totalUnreadNotifications = $unreadMessages + $unreadComments;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<style>
    


    html,
body {
    height: 100%;
    margin: 0;
    overflow-x: hidden;
}

body {
    display: flex;
    flex-direction: column;
}

footer {
    margin-top: auto;
}

.container-fluid {
    padding-bottom: 0;
}

.carousel-item {
    height: 32rem;
    background: #777;
    color: white;
    position: relative;
    background-position: center;
    background-size: cover;
}

.content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding-bottom: 50px;
}

#mainNavbar {
    background-color: white;
    transition: background-color 0.3s ease;
}

#mainNavbar.sticky-top {
    background-color: rgba(255, 255, 255, 0.9);
}

#mainNavbar {
    box-shadow: none;
}

#mainNavbar.sticky-top {
    box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3);
}

.custom-card {
    width: 100%;
    z-index: 10;
    position: relative;
    max-height: 310px;
    overflow: visible;
    margin: 0 auto;
}

.navbar-nav .nav-link,
.navbar-nav .nav-link:focus,
.navbar-nav .nav-link:hover,
.search-input,
.btn-outline-success {
    color: #ffffff;
}

.search-input {
    height: 35px;
    width: 200px;
}

.btn {
    height: 35px;
    padding: 0 15px;
    line-height: 35px;
}

.btn-outline-success,
.btn-outline-danger {
    color: #ffffff;
    border-color: #ffffff;
    width: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-danger.btn-block {
    width: 120px;
    height: 35px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
}

footer .col-md-6 {
    position: relative;
}

footer .col-md-6:nth-child(2) {
    position: absolute;
    top: 0;
    left: 70%;
    transform: translateX(-50%);
    z-index: 1;
    width: 100%;
    text-align: center;
}

footer .col-md-6:nth-child(2) h5 {
    color: #ce3c4a !important;
    font-size: 28px;
}

footer .col-md-6:nth-child(2) .d-flex {
    justify-content: center;
    gap: 20px;
}

footer .col-md-6:nth-child(2) .d-flex a {
    font-size: 35px;
    color: white;
}

/* Shared styles for article content and latest news */
.content-box {
    border: 2px solid #ccc;
    padding: 20px;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.latest-articles-list {
    margin-top: 20px;
}

.latest-article {
    position: relative;
    margin-bottom: 20px;
}

.image-container {
    position: relative;
    overflow: hidden;
}

.image-container img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease; /* Animation for image zoom */
}

.image-container:hover img {
    transform: scale(1.1); /* Zoom in on hover */
}

.article-title-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.5); /* Dark transparent background */
    color: white;
    text-align: center;
    padding: 10px;
    transition: background-color 0.3s ease; /* Transition for background color */
}

.image-container:hover .article-title-overlay p {
    color: blue; /* Change text color to blue on hover */
}

.article-title-overlay p {
    font-size: 16px;
    font-weight: bold;
    margin: 0;
    transition: color 0.3s ease; /* Transition for text color */
}

.image-container:hover .article-title-overlay {
    background: rgba(0, 0, 0, 0.7); /* Make the background darker on hover */
}

/* Footer styles */
footer {
    background-color: #343a40;
    color: white;
    position: relative;
    padding: 2rem 0;
    width: 100%;
}

footer .col-md-6 {
    position: relative;
}

footer .col-md-6:nth-child(2) {
    position: absolute;
    top: 0;
    left: 70%;
    transform: translateX(-50%);
    z-index: 1;
    width: 100%;
    text-align: center;
}

footer .col-md-6:nth-child(2) h5 {
    color: #ce3c4a !important;
    font-size: 28px;
}

footer .col-md-6:nth-child(2) .d-flex {
    justify-content: center;
    gap: 20px;
}

footer .col-md-6:nth-child(2) .d-flex a {
    font-size: 35px;
    color: white;
}

.latest-articles-list h4 {
    text-align: center;
}


a[title]:hover::after {
    content: attr(title);
    background-color: #333;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    position: absolute;
    top: 100%; /* Now positioned below the button */
    right: 0%; /* Centered horizontally */
    transform: translateX(-50%); /* Center the tooltip */
    z-index: 1000;
    white-space: nowrap;
}

</STYLE>

<body>

<header>
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNavbar">
            <div class="container-fluid justify-content-center">
                <a class="navbar-brand" href="#">
                    <img class="d-inline-block align-top" src="NABAA-TV-LOGO1.png" width="400" height="60" />
                </a>
            </div>
        </nav>

        <nav class="navbar navbar-expand-md navbar-dark" style="background-color:#c2272e;">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="politics.php">Politics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="entertainment.php">Entertainment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sports.php">Sports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apinews.php">API News</a>
                </li>
            </ul>
        </div>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <form class="d-flex" role="search">
                           

                             <!-- Admin Notification Button -->
                             <?php if ($isAdmin): ?>
                                <a href="notifications.php" class="btn btn-outline-warning" style="width: 150px;" title="You have <?php echo $totalUnreadNotifications; ?> new notifications">
                        <i class="fa-solid fa-bell"></i> Notifications
                    </a>

                            <?php endif; ?>

                            <?php if (isset($_SESSION['user_id'])): ?>

                                <a href="logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-outline-danger ms-2">Sign in</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    
<div class="container">
<div class="container my-4">
    <div class="row">
        <div class="col-md-12">
            <div class="article-content-container content-box">
                <button id="articleCategory" ></button>
                <h1 id="articleTitle"></h1>
                <p><small id="articleAuthor" class="text-muted"></small></p>
                <img id="articleImage" class="img-fluid my-3" src="" alt="Article Image">
                <p id="articleContent"></p>
            </div>
        </div>
    </div>
</div>

 
 
 
 

        <!-- Bootstrap and Custom Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>

        <!-- Script to Display Full Article Content -->
        <script>
            // Retrieve article details from localStorage
            const article = JSON.parse(decodeURIComponent(localStorage.getItem('selectedArticle')));

            // Display article details on the page
            document.getElementById('articleTitle').innerText = article.title;
            document.getElementById('articleCategory').innerText = article.category || 'API NEWS'; 
            document.getElementById('articleAuthor').innerText = article.author ? `Author: ${article.author}` : 'NABAATV PUBLISHER';
            document.getElementById('articleImage').src = article.urlToImage || 'news-image-placeholder.jpg';
            document.getElementById('articleImage').onerror = function () {
                this.src = 'news-image-placeholder.jpg';
            };

            // Display the available content without truncating
            document.getElementById('articleContent').innerText = article.content || article.description || 'Content not available.';
        </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>



        <footer class="bg-dark text-white" style="padding: 2rem 0; width: 100%;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-lg custom-card">
                    <div class="card-body text-center p-3">
                        <h3 class="card-title text-danger">Contact Us</h3>
                        <!-- Contact Us Form -->
                        <form action="contact.php" method="POST">
                            <!-- Email and Name -->
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="email" class="form-control border-danger" id="email" name="email" placeholder="Enter your email" required>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control border-danger" id="name" name="name" placeholder="Enter your name">
                                </div>
                            </div>
                            <!-- Message -->
                            <div class="form-group mb-3">
                                <textarea name="message" class="form-control border-danger" id="message" placeholder="Your message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block mt-4">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<footer class="bg-dark text-white" style="padding: 2rem 0; width: 100%;">
    <div class="container d-flex justify-content-between">
        <div class="col-md-6" style="margin-right: 100px; margin-top: 90px; color:#cd3d49"> <!-- Added margin to create space -->
            <h3>Your feedback matters! Contact us for assistance.</h3>
        </div>
        <div class="col-md-6">
            <div class="card shadow-lg custom-card">
                <div class="card-body text-center p-3">
                    <h3 class="card-title text-danger">Contact Us</h3>
                    <!-- Contact Us Form -->
                    <form action="contact.php" method="POST">
                        <!-- Email and Name -->
                        <div class="row mb-3">
                            <div class="col">
                                <input type="email" class="form-control border-danger" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control border-danger" id="name" name="name" placeholder="Enter your name">
                            </div>
                        </div>
                        <!-- Message -->
                        <div class="form-group mb-3">
                            <textarea name="message" class="form-control border-danger" id="message" placeholder="Your message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block mt-4">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>


<footer class="bg-secondary text-white" style="padding: 1rem 0; width: 100%; background-color: #2c3236 !important;">
    <div class="container d-flex align-items-center justify-content-between"> <!-- Add justify-content-between -->
        <div style="flex: 0 0 auto; margin-right:100px;"> <!-- Keep the Back to Top button at the far left -->
            <a href="#top" class="text-white" style="text-decoration: none;">
                <i class="fa fa-arrow-up"></i> Back to Top
            </a>
        </div>

        <div class="flex-grow-1 text-center"> <!-- Centering the social links -->
            <div class="d-flex justify-content-center align-items-center">
                <span class="text-white" style="text-decoration: none; font-size: 15px; margin-right: 10px;">Follow us on our socials</span>
                <a href="https://www.facebook.com/Nabaatvcom" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-x"></i>
                </a>
                <a href="#" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="btn-floating text-white" style="font-size: 20px;">
                    <i class="fab fa-threads"></i>
                </a>
            </div>
        </div>

        <div style="flex: 0 0 auto; font-size: 15px; margin-left: 20px;"> <!-- Text on the far right -->
            © 2024, All Rights Reserved | Developed & Designed by GO CREATIVE
        </div>
    </div>
</footer>

</body>

</html>
 
 
 
 


