<!DOCTYPE html>
<html lang="en">
<?php
    $pageTitle = "Register";
    include 'head.php';
?>
<body>
    <?php include_once("navbar.php"); ?>
    
    <div class="container login-container register">
        <h1>Register</h1>
        <form action="./backend/register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>  
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name">
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Surname</label>
                <input type="text" name="surname" class="form-control" id="surname">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" id="phone">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea style="resize: none;" name="address" class="form-control" id="address" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input type="date" name="birthdate" class="form-control" id="birthdate">
            </div>
            <button type="submit" class="btn">Register</button>
            <a href="login.php" class="btn float-right">Back to Login</a>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>