<?php
    include_once(__DIR__ . "/bootstrap.php");
    $loginwarning = " ";
    if(!empty($_POST)){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = new User();
        if($user->canLogin($username, $password)){
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user->getId($username);
            if($user->canLoginAdmin($username, $password)){
                $_SESSION['admin'] = true;
                header("Location: index.php");
            }
            else{
                $_SESSION['admin'] = false;
                header("Location: index.php");
            }
        }
        else{
            $loginwarning = "Username or password is incorrect, or email is not verified";
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
    <title>Eve - Log in</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <div class="form flex flex-row">
        <div class = "w-1/2 h-100 flex justify-center items-center">
            <form action="" method="post">
            <img  src="<?php echo $url."evestore/assets/brand/od3krbvhegihsaahirrz.png"?>" class="flex justify-center w-50 ml-10 mb-10">
                <h1 class="mb-15 text-[#0464A4] text-5xl mb-10">Log in</h1>
                <ul>
                    <li ><input class="border-2 flex w-full justify-center rounded-md mb-5 py-2" type="text" name="username" placeholder="Username" required></li>
                    <li><input class="border-2 flex w-full justify-center rounded-md mb-10 py-2" type="password" name="password" placeholder="Password" required></li>
                    <li><input type="submit" value="Log in" class="flex w-full justify-center mb-5 rounded-md bg-[#0464A4] py-3 text-sm font-semibold text-white hover:bg-[#0444A4] cursor-pointer"></li>
                    <li class="mb-5 text-[#0464A4]"><a href="forgotpassword.php" >Forgot password?</a></li>
                    <li>Don't have an account? <a href="register.php" class="text-[#0464A4]">Register</a></li>
                    <li class="text-[#FF0000]"><?php echo $loginwarning ?></li>
                </ul>
                
            </form>
        </div>
        <div class="w-1/2">
            <img  src="<?php echo $url."evestore/assets/images/nz01zmtboksuyko7cmam.jpg"?>" class="w-full h-screen">
        </div>
    </div>

</body>
</html>
