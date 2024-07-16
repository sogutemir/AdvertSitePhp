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
$pageTitle = "New Advert";
include 'head.php';
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
        <form class="row login-container save-advert" action="./backend/advert.php" method="post" enctype="multipart/form-data">
            <h1 class="col-12" style="text-align: center;">Save Advert</h1>
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
                        <input type="text" name="title" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <input type="text" name="price" class="form-control" id="price">
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
                <button type="submit" class="btn">Save Advert</button>
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

                photoSlider.slick({
                    dots: true,
                    infinite: true,
                    slidesToShow: 1
                });
            });

            checkFeatures();
        });



        function checkFeatures() {
            var featuresdiv = $("#features");

            if (features === 0) {

                featuresdiv.html('<h1 class="col-12" style="text-align: center;">Features</h1><p class="col-12" style="text-align: center;">This section is currently empty.</p>');
            } else {

                featuresdiv.html('<h1 class="col-12" style="text-align: center;">Features</h1>');
            }
        }
    </script>


</body>

</html>