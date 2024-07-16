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

    $full_name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $stmt = $conn->prepare("INSERT INTO contact (email, message, full_name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $message, $full_name);

    if ($stmt->execute()) {
        echo '<script>alert("Mesajınız başarıyla gönderildi.");</script>';
        echo '<script>window.location.href = "http://localhost:8080/AdvertSitePhp/";</script>';
    } else {
        echo '<script>alert("Hata.");</script>' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
