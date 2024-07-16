<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'head.php'; ?>
    <meta charset="UTF-8">
    <title>Contact</title>
</head>
<body>
<?php include_once("navbar.php"); ?>

    <div class="container contact-container">
        <h1>Contact</h1>
        <p>Do not hesitate to contact us. You can reach us by filling out the form below.</p>
        
        <form action="backend\contact-form-handler.php" method="post">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Send</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
