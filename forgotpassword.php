<?php
    require_once 'vendor/autoload.php';
    include_once(__DIR__ . "/classes/User.php");
    include_once(__DIR__ . "/classes/Db.php");
    $error = " ";
    $n = 5;
    $config = parse_ini_file('config/config.ini', true);
    $key = $config['keys']['SENDGRID_API_KEY'];
    $user = new User();
    $email;
    apache_setenv('SENDGRID_API_KEY', $key);
    session_start();
    if(isset($_POST)){
        if(isset($_POST['btn'])){
                try {
                    //code...
                    if($user->setEmail($_POST['email']) == true){
                        $email = $_POST['email'];
                        $confirmation_code = $user->setRandomString($n).$_SESSION['id'];
                        $user->setPsswdToken($confirmation_code);
                        $user->sendResetEmail();
                        $_SESSION['resetting'] = true;
                        $_SESSION['psswdToken'] = $confirmation_code;
                        $code = false;
                    }
                    else{
                        echo "User does not exist!";
                    }
                } catch (\Throwable $th) {
                    //throw $th; 
                    $nomail = $th->getMessage();
                }
        }
        if(isset($_POST['code'])){
            if($_POST['code'] == $_SESSION['psswdToken']){
                $code = true;
            }
            else{
                $error = "Invalid code";
            }
        }
        if(isset($_POST['reset-password'])){
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            if($password == $password2){
                $user->setPassword($password);
                $user->updatePassword($email);
                $_SESSION['resetting'] = false;
                // header("Location: login.php");
            }
            else{
                $error = "Passwords don't match";
                $code = true;
            }
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
    <title>Reset your password</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <?php if(!isset($code)): ?>
    <form action="" method="post">
            <h1>Reset password</h1>
            <ul>
                <li><input type="text" name="email" placeholder="email" required></li>
                <li><input type="submit" value="send Email" name="btn"></li>
            </ul>
            
            <?php if(isset($nomail)): ?>
                <div> <?php echo $nomail ?></div>        
            <?php endif; ?>
    </form>
    <?php elseif($code == true):?>
    <form action="" method="post">
        <h1>Reset your password</h1>
        <ul>
            <li><input type="password" name="password" placeholder="password" required></li>
            <li><input type="password" name="password2" placeholder="confirm password" required></li>
            <li><input type="submit" value="reset password" name="reset-password"></li>
            <li><p><?php echo $error ?></p></li>
        </ul>
    </form>
    <?php elseif($code == false): ?>
    <form action="" method="post">
        <h1>Email has been sent!</h1>
        <ul>
            <li><input type="text" name="code" placeholder="code" required></li>
            <li><input type="submit" value="reset password" name="reset-code"></li>
            <li><p><?php echo $error ?></p></li>
        </ul>
    </form>
    <?php endif; ?>
</body>
</html>