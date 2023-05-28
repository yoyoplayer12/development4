<nav class="bg-[#040404]">
    <?php
    //setting up image getting
    $image = new Image();
    $url = $image->getUrl();
    ?>
    <div class="flex items-center justify-between py-4 px-8 md:px-4">
        <a href="index.php" class="flex items-center">
            <img src="<?php echo $url . 'evestore/assets/brand/od3krbvhegihsaahirrz.png'; ?>" alt="Eve" class="navlogo h-8 mr-4">
        </a>
        <button id="menu-toggle" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6 text-[#F4F4FC]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <ul id="menu" class="hidden md:flex md:items-center md:gap-5 md:bg-transparent md:py-4 md:px-8 absolute top-16 left-0 w-full z-10 md:static md:w-auto md:ml-0 <?php echo(isset($_SESSION["loggedin"]) ? 'bg-black' : ''); ?>">
            <li><a href="index.php" class="block py-2 text-2xl text-white hover:text-[#0464A4]">Home</a></li>
            <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == true) : ?>
                <li><a href="moderator.php" class="block py-2 text-2xl text-white hover:text-[#0464A4]">Moderator</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION["loggedin"])) : ?>
                <li><a href="createprompt.php" class="block py-2 text-2xl text-white hover:text-[#0464A4]">New prompt</a></li>
                <li><a href="profile.php" class="block py-2 text-2xl text-white hover:text-[#0464A4]"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li class="text-blue-500 text-2xl"><?php $balance = User::getBalance();
                echo $balance; ?> Credits</li>
            <?php else : ?>
                <li><a href="login.php" class="block py-2 text-2xl text-white hover:text-[#0464A4]">Log In</a></li>
                <li><a href="register.php" class="block py-2 text-2xl text-white hover:text-[#0464A4]">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    menuToggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
