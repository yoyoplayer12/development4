<nav>
    <ul class="navbar">
        <li><img src="assets/logo.png" alt="Eve" class="navlogo"></li>
        <li><a href="index.php"><h1>Home</h1></a></li>
        <?php if(isset($_SESSION["admin"])): ?>
        <li><a href="adminpanel.php"><h1>Admin panel</h1></a></li>
        <?php endif; ?>
        <?php if(isset($_SESSION["loggedin"])): ?>
        <li><a href="profile.php"><h1><?php echo $_SESSION['username']; ?></h1></a></li>
        <?php else:?>
        <li><a href="login.php"><h1>Log In</h1></a></li>
        <?php endif;?>
    </ul>
</nav>