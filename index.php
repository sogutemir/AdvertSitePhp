<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include_once("navbar.php"); ?>
    
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
        <header class="main-header">
            <div class="container">
                <h1>Welcome!</h1>
                <p>Explore, advertise and find with us.</p>
                <a href="register.php" class="btn btn-primary">Register</a>
                <a href="login.php" class="btn btn-secondary">Login</a>
            </div>
        </header>
    <?php endif; ?>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "advertphp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT advert.id, advert.title, advert.description, advert.price, 
               (SELECT photo FROM advert_photo WHERE advert_photo.advert_id = advert.id LIMIT 1) AS photo,
               COALESCE(AVG(advert_comment.star), 0) AS average_star
        FROM advert
        LEFT JOIN advert_comment ON advert.id = advert_comment.advert_id
        GROUP BY advert.id
        ORDER BY average_star DESC, advert.id DESC
        LIMIT 10"; 

$result = $conn->query($sql);

$featuredAdverts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredAdverts[] = $row;
    }
}


?>

<section class="featured-adverts">
    <div class="container">
        <h2>Featured Ads</h2>
        <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($featuredAdverts as $key => $advert): ?>
                    <?php if (!empty($advert['photo'])): ?>
                        <div class="carousel-item <?php echo ($key == 0) ? 'active' : ''; ?>">
                            <a href="/AdvertSitePhp/advert-detail.php?id=<?php echo $advert['id']; ?>&title=<?php echo urlencode($advert['title']); ?>">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($advert['photo']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($advert['title']); ?>">
                            </a>
                            <div class="carousel-caption">
                                <h5><?php echo htmlspecialchars($advert['title']); ?></h5>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php if (count($featuredAdverts) > 1): ?>
                <a class="carousel-control-prev" href="#featuredCarousel" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#featuredCarousel" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

    <section class="how-it-works">
        <div class="container">
            <h2>How It Works</h2>
            <p>Posting an ad or browsing listings on our site is quite simple. Here are the steps:</p>
            <div class="steps">
                <div class="step">
                    <h3>Sign Up</h3>
                    <p>The first step is to sign up on our site. You can start by clicking the 'Register' button.</p>
                </div>
                <div class="step">
                    <h3>Create Your Listing</h3>
                    <p>After signing up, click on the 'Post Ad' option to easily create your listing.</p>
                </div>
                <div class="step">
                    <h3>Publish Your Ad</h3>
                    <p>Once you've tailored your ad to your satisfaction, hit the 'Publish' button to make your ad go live.</p>
                </div>
                <div class="step">
                    <h3>Browse Listings</h3>
                    <p>Use the 'Browse Ads' option to explore listings posted by other users.</p>
                </div>
                <div class="step">
                    <h3>Get in Touch</h3>
                    <p>If you find an ad that interests you, you can directly contact the ad owner.</p>
                </div>
            </div>
        </div>
    </section>


    <footer>
        <div class="container">
            <?php include 'footer.php'; ?>
        </div>
    </footer>

    <script src="path_to_jquery"></script>
    <script src="path_to_popper"></script>
    <script src="path_to_bootstrap_js"></script>
</body>
</html>