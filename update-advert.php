<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {

    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = $_GET['title'] . " - Update Advert";
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
        $photo['photo'] = base64_encode($photo['photo']);
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
?>
<style>
    h1,
    label,
    .form-control,
    .btn {
        font-family: 'Arial', sans-serif;
    }

    h1 {
        font-size: 24px;
        font-weight: bold;
    }

    .form-label {
        font-size: 14px;
        font-weight: normal;
    }

    .form-control {
        font-size: 16px;
        font-weight: normal;
    }

    .btn {
        font-size: 18px;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .login-container.save-advert {
            width: auto;
        }

        .form-control,
        .btn {
            font-size: 14px;
        }

    }
</style>

<body>
    <?php include_once("navbar.php"); ?>
    <div class="container">
        <form class="row login-container save-advert" action="./backend/advert.php?id=<?php echo $advertData['ID']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            <?php
                foreach ($photosData as $photo) {
                    echo '<input type="hidden" name="old_photos[]" value="' . $photo['photo'] . '">';
                }            
            ?>
            <h1 class="col-12" style="text-align: center;">Update Advert</h1>
            <div class="col-4">
                <div id="photoSlider"></div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Photos</label>
                    <input type="file" name="photo[]" class="form-control" id="photo" multiple>
                </div>
            </div>
            <div class="col-4">
                <div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" value="<?php echo $advertData['title']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3"><?php echo $advertData['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <input type="text" name="price" class="form-control" id="price" value="<?php echo $advertData['price']; ?>">
                            <span class="input-group-text">$</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row" id="features">
                    <h1 class="col-12" style="text-align: center;">Features</h1>
                </div>
            </div>
            <div style="text-align: center;" class="col-12">
                <button type="button" class="btn" onclick="addTextBoxes()">Add Feature</button>
                <button type="submit" class="btn">Update Advert</button>
            </div>
        </form>

    </div>

    <script>
        let features = 0;

        function addTextBoxes() {
            features++;
            if (features === 1) {
                checkFeatures();
            }
            var featuresdiv = $("#features");

            var TextBox = $('<div class="mb-3 col-6"><label for="name" class="form-label">Name</label><input type="text" name="names[]" class="form-control" id="name"></div><div class="mb-3 col-6"><label for="value" class="form-label">Value</label><input type="text" name="values[]" class="form-control" id="value"></div>');

            featuresdiv.append(TextBox);

        }

        function validatePrice(input) {
            var regex = /^[0-9]+$/;

            if (input.value.trim() !== "" && !regex.test(input.value)) {
                alert("Please enter a valid numeric value for the price.");
                input.value = input.value.replace(/[^\d]/g, '');
            }
        }

        $(document).ready(function() {
            var photoSlider = $('#photoSlider');

            $('#photo').on('change', function() {
                var files = $(this)[0].files;

                if (photoSlider.hasClass('slick-initialized')) {
                    photoSlider.slick('unslick');
                    photoSlider.html('');
                }

                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var img = $('<img>').attr('src', e.target.result);
                        photoSlider.slick('slickAdd', img);
                    };

                    reader.readAsDataURL(files[i]);
                }

                // Slider'ı başlat
                photoSlider.slick({
                    dots: true,
                    infinite: true,
                    slidesToShow: 1
                });
            });

            fillFields();
            fillPhotos();
            checkFeatures();
        });

        function checkFeatures() {
            var featuresdiv = $("#features");

            if (features === 0) {

                featuresdiv.html('<h1 class="col-12" style="text-align: center;">Features</h1><p class="col-12" style="text-align: center;">This section is currently empty.</p>');
            }
        }

        function fillFields() {
            var featuresdiv = $("#features");
            var fieldsData = <?php echo json_encode($fieldsData); ?>;

            for (var i = 0; i < fieldsData.length; i++) {
                console.log("Adding textbox for:", fieldsData[i]);
                features++;
                if (features === 1) {
                    checkFeatures();
                }

                var TextBox = $(
                    '<div class="mb-3 col-6"><label for="name" class="form-label">Name</label><input type="text" name="names[]" class="form-control" id="name" value="' + fieldsData[i].name + '"></div>' +
                    '<div class="mb-3 col-6"><label for="value" class="form-label">Value</label><input type="text" name="values[]" class="form-control" id="value" value="' + fieldsData[i].value + '"></div>'
                );

                featuresdiv.append(TextBox);
            }
        }

        function fillPhotos() {
            var photoSlider = $('#photoSlider');
            photoSlider.html('');

            var photosData = <?php echo json_encode($photosData); ?>;
            console.log(photosData);

            for (var i = 0; i < photosData.length; i++) {

                var img = $("<img>").attr('src', 'data:image/jpeg;base64,' + photosData[i].photo);
                photoSlider.append(img);

            }

            photoSlider.slick({
                dots: true,
                infinite: true,
                slidesToShow: 1,
                adaptiveHeight: true
            });
        }
    </script>
</body>

</html>