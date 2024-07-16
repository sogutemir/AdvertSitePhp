<?php
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "advertphp";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $birthdate = $_POST["birthdate"];

    $checkUsernameSql = "SELECT id FROM account WHERE username = '$username'";
    $resultUsername = $conn->query($checkUsernameSql);

    if ($resultUsername->num_rows > 0) {

        echo '<script>alert("Username already exists. Please choose a different username.");</script>';
        echo '<script>window.location.href = "http://localhost:8080/AdvertSitePhp/register.php";</script>';
    } else {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO user (email, name, surname, phone, address, birthdate)
                VALUES ('$email', '$name', '$surname', '$phone', '$address', '$birthdate')";

        if ($conn->query($sql) === TRUE) {

            $sql1 = "SELECT id FROM user WHERE (email = '$email' AND name = '$name' AND surname = '$surname' AND phone = '$phone' AND address = '$address' AND birthdate = '$birthdate')";
            $result = $conn->query($sql1);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user_id = $row["id"];
                }
            } else {
                echo "Data not found.";
            }

            $sql2 = "INSERT INTO account (user_id, username, password) VALUES ('$user_id', '$username', '$hashedPassword')";

            if ($conn->query($sql2) === TRUE) {
                echo '<script>alert("Registration successful!");</script>';
                echo '<script>window.location.href = "http://localhost:8080/AdvertSitePhp/login.php";</script>';
            } else {
                die("Error: " . $sql2 . "<br>" . $conn->error);
            }
        } else {
            die("Error: " . $sql . "<br>" . $conn->error);
        }
    }

    // Close the database connection
    $conn->close();
}
