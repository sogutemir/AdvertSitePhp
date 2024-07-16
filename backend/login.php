<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "advertphp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$sql = "SELECT * FROM account WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    if (password_verify($password, $hashedPassword)) {

        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $row['user_id'];

        header('Location: http://localhost:8080/AdvertSitePhp/index.php');
        exit();
    } else {
        
        echo '<script>alert("Wrong username or password.");</script>';
        echo '<script>window.location.href = "http://localhost:8080/AdvertSitePhp/login.php";</script>';
        exit();
    }
} else {

    echo '<script>alert("Wrong username or password.");</script>';
    echo '<script>window.location.href = "http://localhost:8080/AdvertSitePhp/login.php";</script>';
    exit();
}

$conn->close();
?>
