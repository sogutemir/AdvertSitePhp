<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = $_GET['title'] . " - Advert Details";
include 'head.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "advertphp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$targetAdvertId = isset($_GET['id']) ? $_GET['id'] : null;

if ($targetAdvertId === null) {
    die("Advert ID not provided");
}

$sql = "SELECT * FROM advert WHERE id = $targetAdvertId";

$resultAdvert = $conn->query($sql);

if ($resultAdvert->num_rows > 0) {
    $advertData = $resultAdvert->fetch_assoc();  

    $sql1 = "SELECT * FROM advert_photo WHERE advert_id = $targetAdvertId";
    $resultPhotos = $conn->query($sql1);

    $photosData = [];
    while ($photo = $resultPhotos->fetch_assoc()) {
        $photosData[] = $photo;
    }

    $sql2 = "SELECT * FROM advert_field WHERE advert_id = $targetAdvertId";
    $resultFields = $conn->query($sql2);

    $fieldsData = [];
    while ($field = $resultFields->fetch_assoc()) {
        $fieldsData[] = $field;
    }
} else {
    echo "No data found";
}

function display_stars($rating) {
    $output = '';
    for ($i = 0; $i < 5; $i++) {
        if ($i < $rating) {
            $output .= '&#9733;'; 
        } else {
            $output .= '&#9734;';
        }
    }
    return $output;
}

?>

<body>
    <?php include_once("navbar.php"); ?>
    <div class="container login-container save-advert row">
        <div class="col-8">
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <?php
                    foreach ($photosData as $key => $photo) {
                        $activeClass = ($key == 0) ? 'active' : '';
                        echo "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='$key' class='$activeClass' aria-label='Slide " . ($key + 1) . "'></button>";
                    }
                    ?>
                </div>
                <?php
                if($resultPhotos->num_rows === 0) {
                    echo "<div class='carousel-item active'>";
                    echo "<img src='https://via.placeholder.com/600x400?text=No+photos' class='d-block w-100' alt='...'>";
                    echo "</div>";
                }
                else {
                    echo '<div class="carousel-inner">';
               
                    foreach ($photosData as $key => $photo) {
                        $activeClass = ($key == 0) ? 'active' : '';
                        echo "<div class='carousel-item $activeClass'>";
                        echo "<img src='data:image/jpeg;base64," . base64_encode($photo['photo']) . "' class='d-block w-100' alt='...'>";
                        echo "</div>";
                    }
                    echo '</div>';
                echo '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>';
                echo '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>';
                }
                ?>
            </div>
        </div>
        <div class="col-4">
            <h1><?php echo $advertData['title']; ?></h1>
            <div style="margin-top: 25px;">
            <?php
                // İlan için yapılan yorumların ortalama yıldız puanını al
                $sqlAverageStars = "SELECT AVG(star) as average_star FROM advert_comment WHERE advert_id = ?";
                $stmt = $conn->prepare($sqlAverageStars);
                $stmt->bind_param("i", $targetAdvertId);
                $stmt->execute();
                $resultAverageStars = $stmt->get_result();
                $averageStarsRow = $resultAverageStars->fetch_assoc();
                $averageStars = $averageStarsRow['average_star'] ?? 0; // Eğer sonuç boşsa, 0 değeri atanacak

                // Daha sonra, ilanın fiyat bilgisi yanında ortalama yıldızları göster
            ?>

            <h4>Price: <?php echo $advertData['price']; ?> $ <span class="average-stars"><?php echo display_stars(round($averageStars)); ?></span></h4>

                <h4 style="text-align:center;">Features</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Value</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <?php
                            foreach ($fieldsData as $field) {
                                echo "<tr>";
                                echo "<td>" . $field['name'] . "</td>";
                                echo "<td>" . $field['value'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <h4 style="text-align:center;">Description</h4>
                <p style="word-wrap: break-word;"><?php echo htmlspecialchars($advertData['description']); ?></p>
            </div>
        </div>
    </div>
    <div class="container comments-container">
        <div class="row">
            <div class="col-12">
                <!-- Yorum ve Puanlama Formu -->
                <!-- <div class="comment-rating-form">
                    <h3>Leave a Comment and Rating</h3>
                    <form action="backend/comment_rattings.php" method="post">
                        <!-- Form alanları ... -->
                    </form>
                </div>

                <div class="comments-ratings">
                <h3>Comments and Ratings</h3>
                <div class="comment-list">
                        <?php
                        $sql3 = "SELECT * FROM advert_comment WHERE advert_id = $targetAdvertId ORDER BY ID DESC";
                        $resultComments = $conn->query($sql3);



                        if ($resultComments->num_rows > 0) {
                            while ($comment = $resultComments->fetch_assoc()) {
                                echo "<div class='comment-rating-card'>";
                                echo "<div class='comment-title'>" . htmlspecialchars($comment['title']) . "</div>";
                                echo "<div class='comment-rating'>" . display_stars($comment['star']) . "</div>";
                                echo "<div class='comment-text'>" . htmlspecialchars($comment['comment']) . "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='no-comments'>No comments or ratings yet.</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="comment-rating-form login-container save-advert">
                <h3>Leave a Comment and Rating</h3>
                <form action="backend\comment_rattings.php" method="post">
                    <input type="hidden" name="advert_id" value="<?php echo $targetAdvertId; ?>">
                    <input type="hidden" name="advert_title"value="<?php echo $advertData['title']; ?>">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea name="comment" class="form-control" required></textarea>
                    </div>
                    <div class="form-group rating">
                    <label for="rating">Rating:</label>
                        <div class="rating-stars">
                            <input id="star5" name="star" type="radio" value="5" class="star"/><label for="star5">&#9733;</label>
                            <input id="star4" name="star" type="radio" value="4" class="star"/><label for="star4">&#9733;</label>
                            <input id="star3" name="star" type="radio" value="3" class="star"/><label for="star3">&#9733;</label>
                            <input id="star2" name="star" type="radio" value="2" class="star"/><label for="star2">&#9733;</label>
                            <input id="star1" name="star" type="radio" value="1" class="star"/><label for="star1">&#9733;</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
    </div>
</body>

</html>
