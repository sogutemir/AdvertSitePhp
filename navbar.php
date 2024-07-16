<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">Home</a>
                </li>
                <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="adverts.php">Adverts</a>
                    </li>
                <?php else : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Adverts
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="adverts.php">Adverts</a></li>
                        <li><a class="dropdown-item" href="my-adverts.php?userId=<?php echo $_SESSION['user_id']; ?>">My Adverts</a></li>
                        <li><a class="dropdown-item" href="new-advert.php">New Advert</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
            <div class="navbar-nav ml-auto">
                <li class="nav-greeting">Welcome, <?php echo $_SESSION['username']; ?>!</li>
                <li class="nav-item"><a class="nav-link" href="./backend/logout.php"><i class="fa-solid fa-user-slash"></i> Logout</a></li>
            </div>
        <?php else : ?>
            <div class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="login.php"><i class="fa-solid fa-user"></i> Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php"><i class="fa-solid fa-user-plus"></i> Register</a></li>
            </div>
        <?php endif; ?>
        <!-- <div class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="login.php"><i class="fa-solid fa-user"></i> Login</a>
            </li>
        </div> -->
    </div>
</nav>