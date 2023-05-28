<?php
include_once(__DIR__ . "/bootstrap.php");
$config = parse_ini_file(__DIR__ . "/classes/config/config.ini");
$key = $config['SENDGRID_API_KEY'];
apache_setenv('SENDGRID_API_KEY', $key);
if (isset($_POST['registerBtn'])) {
    if (!empty($_POST)) {
        try {
            $user = new User();
            try {
                $user->setEmail($_POST['email']);
            } catch (\Throwable $th) {
                $emailError = $th->getMessage();
            }
            try {
                $user->setUsername($_POST['username']);
            } catch (\Throwable $th) {
                $usernameError = $th->getMessage();
            }
            try {
                $user->setPassword($_POST['password']);
            } catch (\Throwable $th) {
                $passwordError = $th->getMessage();
            }
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            $user->setToken($token);
            $user->save();
            $user->sendConfirmEmail();
            header("Location: index.php");
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }
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
    <title>Eve - Sign up</title>
    <link href="normalize.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>
<!-- Sign-up Page -->
<div class="form flex flex-row justify-center items-center">
    <div class="w-full sm:w-1/2 h-screen flex justify-center items-center">
        <form action="" method="post">
            <img src="<?php echo $url . 'evestore/assets/brand/od3krbvhegihsaahirrz.png' ?>" class="sm:block w-50 ml-10 mb-10">
            <h1 class="text-[#0464A4] text-5xl mb-20 text-center">Sign Up</h1>
            <div class="flex flex-col items-center gap-5">
                <div>
                    <input type="text" name="email" placeholder="Email" class="border-2 px-4 py-2 rounded-md mb-15 text-base" id="email" onkeyup="checkEmailAvailability()">
                    <?php if (isset($emailError)) : ?>
                        <p class="text-[#FF0000]"><?php echo $emailError; ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="text" name="username" placeholder="Username" class="border-2 px-4 py-2 rounded-md mb-15 text-base">
                    <?php if (isset($usernameError)) : ?>
                        <p class="text-[#FF0000]"><?php echo $usernameError; ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" class="border-2 px-4 py-2 rounded-md mb-15 text-base">
                    <?php if (isset($passwordError)) : ?>
                        <p class="text-[#FF0000]"><?php echo $passwordError; ?></p>
                    <?php endif; ?>
                    <div id="feedback"></div>
                </div>
                <div>
                    <input type="submit" value="Sign up" name="registerBtn" class="px-8 py-3 rounded-md bg-[#0464A4] hover:bg-[#0444A4] font-semibold text-white cursor-pointer mb-5">
                </div>
                <p>Already have an account? <a href="login.php" class="text-[#0464A4]">Log in</a></p>
            </div>
        </form>
    </div>
    <div class="hidden sm:block w-1/2">
        <img src="<?php echo $url . 'evestore/assets/images/nz01zmtboksuyko7cmam.jpg' ?>" class="w-full h-screen">
    </div>
</div>

</body>
<script>
    function checkEmailAvailability() {
        var email = $('#email').val();
        $.ajax({
            url: 'emailcheck.action.php',
            type: 'POST',
            data: {
                email: email
            },
            dataType: 'json',
            success: function(response) {
                if (response.available) {
                    $('#feedback').text('This Email is available!').css('color', 'green');
                } else {
                    $('#feedback').text('This account already exists').css('color', 'red');
                }
            }
        });
    }
</script>

</html>