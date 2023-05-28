<?php
include_once(__DIR__ . "/bootstrap.php");
$loginwarning = "";
if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = new User();
    if ($user->canLogin($username, $password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $user->getId($username);
        if ($user->canLoginAdmin($username, $password)) {
            $_SESSION['admin'] = true;
            header("Location: index.php");
        } else {
            $_SESSION['admin'] = false;
            header("Location: index.php");
        }
    } else {
        $loginwarning = "Username or password is <br>incorrect,or email is not verified!";

    }
}
//setting up image getting
$image = new Image();
$url = $image->getUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <title>Eve - Log in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <div class="form flex flex-col sm:flex-row">
    <div class="w-full sm:w-1/2 h-screen flex justify-center items-center">
        <form action="" method="post">
            <img src="<?php echo $url . 'evestore/assets/brand/od3krbvhegihsaahirrz.png' ?>" class="sm:block w-50 ml-10 mb-10">
            <h1 class="mb-15 text-[#0464A4] text-5xl mb-10 text-center">Log in</h1>
            <ul class="flex flex-col items-center">
                <li><input class="border-2 px-4 py-2 rounded-md mb-5 text-base" type="text" name="username" placeholder="Username" required></li>
                <li><input class="border-2 px-4 py-2 rounded-md mb-15 text-base" type="password" name="password" placeholder="Password" required></li>
                <li class="text-[#FF0000] mb-5"><?php echo $loginwarning ?></li>
                <li><input type="submit" value="Log in" class=" px-8 py-3 rounded-md bg-[#0464A4] hover:bg-[#0444A4] font-semibold text-white cursor-pointer mb-5"></li>
                <li class="mb-5 text-[#0464A4]"><a href="forgotpassword.php">Forgot password?</a></li>
                <li>Don't have an account? <a href="register.php" class="text-[#0464A4]">Register</a></li>
            </ul>
        </form>
    </div>
    <div class="hidden sm:block w-full sm:w-1/2">
        <img src="<?php echo $url . 'evestore/assets/images/nz01zmtboksuyko7cmam.jpg' ?>" class="w-full h-screen">
    </div>
</div>








</body>

</html>
