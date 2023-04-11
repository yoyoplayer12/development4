<?php
    include_once(__DIR__ . "/bootstrap.php");
    $loginwarning = " ";
    if(!empty($_POST)){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new User();
        if($user->canLogin($username, $password)){
            $loginwarning = "";
            header("Location: index.php");
        }
        else{
            $loginwarning = "Username or password is incorrect";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Dev4 - Log in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5f5f5]">
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <div class="loginform flex flex-row">
        <div class = "w-1/2 h-screen">
            <form action="" method="post">
                <h1>Log in</h1>
                <ul>
                    <li><input type="text" name="username" placeholder="Username" required></li>
                    <li><input type="password" name="password" placeholder="Password" required></li>
                    <li><input type="submit" value="Log in"></li>
                    <li><a href="register.php">Create an account</a></li>
                    <li class="warningtext"><?php echo $loginwarning ?></li>
                </ul>
                <a href="forgotpassword.php">Forgot password?</a>
            </form>
        </div>
        <div class="w-1/2">
            <img  src="images/loginImg.jpg" alt="0">
        </div>
    </div>

</body>
</html>
