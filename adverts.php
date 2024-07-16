<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Adverts";
include 'head.php';

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "advertphp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order = 'ASC';
if (isset($_GET['sort']) && $_GET['sort'] === 'desc') {
    $order = 'DESC';
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

$sort_price_link = $_SERVER['PHP_SELF'] . "?sort=price&order=" . ($sort === 'price' && $order === 'ASC' ? 'desc' : 'asc');
$sort_stars_link = $_SERVER['PHP_SELF'] . "?sort=average_stars&order=" . ($sort === 'average_stars' && $order === 'ASC' ? 'desc' : 'asc');

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$searchTerm = mysqli_real_escape_string($conn, $search);

$sql = "SELECT advert.*, MIN(advert_photo.photo) AS first_photo,
               COALESCE(AVG(advert_comment.star), 0) AS average_star
        FROM advert
        LEFT JOIN advert_photo ON advert.id = advert_photo.advert_id
        LEFT JOIN advert_comment ON advert.id = advert_comment.advert_id
        WHERE advert.title LIKE '%$searchTerm%' 
        GROUP BY advert.id
        ORDER BY 
            CASE WHEN '$sort' = 'price' THEN advert.price 
                 WHEN '$sort' = 'average_stars' THEN COALESCE(AVG(advert_comment.star), 0)
            END $order";


$result = $conn->query($sql);

if (!$result) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}

function display_starsAdverts($rating) {
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


</style>
<body>
    <?php include_once("navbar.php"); ?>
    <div class="container login-container save-advert row">
        <div class="col-12">
            <h1 style="text-align: center;">Adverts</h1>
        </div>
        <div class="col-12">
        <div class="search-container">
            <form action="" method="get">
                <input type="text" placeholder="Search by title..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Photo</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">
                                <a href="<?php echo $sort_price_link; ?>">Price</a>
                            </th>
                            <th scope="col">
                                <a href="<?php echo $sort_stars_link; ?>">Average Stars</a>
                            </th>
                            <th scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        if ($result->num_rows === 0) {
                            echo "<tr><td colspan='6' style='text-align: center;'>No adverts found</td></tr>";
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="td-photo">
                                        <?php
                                        if (!empty($row['first_photo'])) {
                                            echo "<img src='data:image/jpeg;base64," . base64_encode($row['first_photo']) . "' class='table-img' alt='...'>";
                                        } else {
                                            echo "No photo available";
                                        }
                                        ?>
                                    </td>
                                    <td class="td-title"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td>
                                        <?php
                                        $description = $row['description'];
                                        if (strlen($description) > 155) {
                                            echo htmlspecialchars(substr($description, 0, 155)) . "...";
                                        } else {
                                            echo htmlspecialchars($description);
                                        }
                                        ?>
                                    </td>
                                    <td class="td-photo"><?php echo htmlspecialchars($row['price']); ?> $</td>
                                    <td class="comment-rating"><?php echo display_starsAdverts(round($row['average_star'])); ?></td> <!-- Görsel yıldız gösterimi -->
                                    <td class="td-button">
                                        <a class="btn btn-search" href="advert-detail.php?id=<?php echo $row['ID']; ?>&title=<?php echo $row['title']; ?>">
                                            <i class="fa fa-magnifying-glass"></i>
                                        </a>
                                    </td>
                                    
                                </tr>
                                <?php
                            }
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>