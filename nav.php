<nav>
    <?php
    //setting up image getting
    $image = new Image();
    $url = $image->getUrl();
    ?>
    <ul class="flex bg-[#040404] text-[#F4F4FC] py-4">
        <li><a href="index.php"><img src="<?php echo $url . "evestore/assets/brand/od3krbvhegihsaahirrz.png" ?>" alt="Eve" class="navlogo h-8 flex justify-start ml-8 mr-4"></a></li>
        <li><a href="index.php">
                <h2 class="text-2xl mr-4 hover:text-[#0464A4]">Home</h2>
            </a></li>
        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == true) : ?>
            <li><a href="moderator.php">
                    <h2 class="text-2xl mr-4 hover:text-[#0464A4]">Moderator</h2>
                </a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION["loggedin"])) : ?>
            <li><a href="createprompt.php">
                    <h2 class="text-2xl mr-4 hover:text-[#0464A4]">New prompt</h2>
                </a></li>
            <li><a href="profile.php">
                    <h2 class="text-2xl mr-4 hover:text-[#0464A4]"><?php echo $_SESSION['username']; ?></h2>
                </a></li>
            <li class="text-blue-500 text-2xl">
                <p><?php $balance = User::getBalance();
                    echo $balance; ?> Credits</p>
            </li>
        <?php else : ?>
            <li><a href="login.php">
                    <h2 class="text-2xl mr-4 hover:text-[#0464A4]">Log In</h2>
                </a></li>
            <li><a href="register.php">
                    <h2 class="text-2xl mr-4 hover:text-[#0464A4]">Register</h2>
                </a></li>
        <?php endif; ?>
    </ul>
</nav>