<?php 
include("./ricordami_cookie.php");
?>

<div class="navbar">
    <div class="img-logo">
        <a href="./index.php">
            SaveWithUs
        </a>
    </div>
    <div class="links">
        <a href="campagne.php">Campagne</a>
        <a href="blogs.php">Blog</a>
        <?php
        if (isset($_SESSION['iduser'])) {
            echo '<a href="user.php">Ciao, ' . $_SESSION['username'] . '</a>';
        } else {
            echo '<a href="login.php">Login</a>';
        }
        ?>
    </div>
</div>