<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "advertphp";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $advert_id = $_POST["advert_id"]; 
    $comment = $_POST["comment"];
    $star = $_POST["star"];
    $title = $_POST["title"]; 
    $advert_title=$_POST["advert_title"];

    $stmt = $conn->prepare("INSERT INTO advert_comment (advert_id, title, comment, star) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $advert_id, $title, $comment, $star);
   

    if ($stmt->execute()) {
        echo '<script>alert("Comment and rating are successfully saved.");</script>';
        echo '<script>window.location.href = "http://localhost:8080/AdvertSitePhp/advert-detail.php?id='.$advert_id.'&title='.$advert_title.'"</script>';
    } else {
        echo '<script>alert("Error !");</script>'. $stmt->error;

    }

    $stmt->close();
    $conn->close();
}
?>
