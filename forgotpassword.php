<?php
include_once(__DIR__ . "/bootstrap.php");
$error = "";
$changed = false;
$n = 5;
$config = parse_ini_file('classes/config/config.ini', true);
$key = $config['keys']['SENDGRID_API_KEY'];
apache_setenv('SENDGRID_API_KEY', $key);
$user = new User();
if (isset($_POST)) {
    if (isset($_POST['btn'])) {
        try {
            //code...
            if ($user->setEmail($_POST['email']) == true) {
                $user->setEmail($_POST['email']);
                $_SESSION["email"] = $user->getEmail();
                $confirmation_code = $user->setRandomString($n) . $_SESSION['id'];
                $user->setPsswdToken($confirmation_code);
                $user->sendResetEmail();
                $_SESSION['psswdToken'] = $confirmation_code;
                $code = false;
            } else {
                $userDontExist = "User does not exist!";
            }
        } catch (\Throwable $th) {
            //throw $th;
            $nomail = $th->getMessage();
        }
    }
    if (isset($_POST['code'])) {
        if ($_POST['code'] == $_SESSION['psswdToken']) {
            $code = true;
        } else {
            $error = "Invalid code!";
        }
    }
    if (isset($_POST['reset-password'])) {
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        if ($password == $password2) {
            $user->setPassword($password);
            $user->updatePassword();
            $changed = true;
        } else {
            $error = "Passwords don't match!";
            $code = true;
        }
    }
}
//setting up image getting
$image = new Image();
$url = $image->getUrl()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <title>Reset your password</title>
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <div >
        <?php if ($changed == true) : ?>
            <h1 class="mb-15 text-[#0464A4] text-5xl flex justify-center my-10">Password changed!</h1>
            <div class="flex justify-center">
                <div class="flex justify-center rounded-md bg-[#0464A4] py-3 text-sm w-80">
                    <a href="login.php" class="font-semibold text-white hover:bg-[#0444A4] cursor-pointer justify-center">Log in</a>
                </div>
            </div>
        <?php elseif (!isset($code)) : ?>
            <h1 class="mb-15 text-[#0464A4] text-5xl flex justify-center my-10">Reset your password</h1>
            <form action="" method="post" class="flex justify-center">
                <ul>
                    <li><input class="border-2 flex w-80 justify-center rounded-md mb-5 mt-5 py-2 p-2" type="text" name="email" placeholder="Email" required></li>
                    <li><input class="w-80 mb-5 rounded-md bg-[#0464A4] py-3 text-sm font-semibold text-white hover:bg-[#0444A4] cursor-pointer" type="submit" value="Send email" name="btn"></li>
                </ul>
                <?php if (isset($nomail)) : ?>
                    <div class="flex justify-center text-[#FF0000] mb-5"><?php echo $nomail ?></div>
                <?php endif; ?>
            </form>
        <?php elseif ($code == false) : ?>
            <h1 class="mb-15 text-[#0464A4] text-5xl flex justify-center my-10">Email has been sent!</h1>
            <form action="" method="post" class="flex justify-center">
                <ul>
                    <li><input type="text" name="code" placeholder="code" required class="border-2 flex w-80 justify-center rounded-md mb-5 mt-5 py-2 p-2"></li>
                    <li><input type="submit" value="reset password" name="reset-code" class="w-80 mb-5 rounded-md bg-[#0464A4] py-3 text-sm font-semibold text-white hover:bg-[#0444A4] cursor-pointer"></li>
                    <li>
                        <p class="flex justify-center text-[#FF0000] mb-5"><?php echo $error ?></p>
                    </li>
                </ul>
            </form>
        <?php elseif ($code == true) : ?>
            <h1 class="mb-15 text-[#0464A4] text-5xl flex justify-center my-10">Reset your password:</h1>
            <form action="" method="post" class="flex justify-center">
                <ul>
                    <li><input type="password" name="password" placeholder="password" required class="border-2 flex w-80 justify-center rounded-md mb-5 mt-5 py-2 p-2"></li>
                    <li><input type="password" name="password2" placeholder="confirm password" required class="border-2 flex w-80 justify-center rounded-md mb-5 mt-5 py-2 p-2"></li>
                    <li><input type="submit" value="reset password" name="reset-password" class="w-80 mb-5 rounded-md bg-[#0464A4] py-3 text-sm font-semibold text-white hover:bg-[#0444A4] cursor-pointer"></li>
                    <li>
                        <p class="flex justify-center text-[#FF0000] mb-5"><?php echo $error ?></p>
                    </li>
                </ul>
            </form>
        <?php endif; ?>

        <!-- error message if user dont exist -->
        <?php if (isset($userDontExist)) : ?>
            <div class="flex justify-center text-[#FF0000] mb-5"><?php echo $userDontExist ?></div>
        <?php endif; ?>
    </div>
</body>

</html>