<?php 
    require_once 'vendor/autoload.php';
    include_once(__DIR__ . "/classes/User.php");
    include_once(__DIR__ . "/classes/Db.php");

    $config = parse_ini_file('config/config.ini', true);
    $key = $config['keys']['SENDGRID_API_KEY'];
    apache_setenv('SENDGRID_API_KEY', $key);

    if(isset($_POST['btn'])){
        
        if(!empty($_POST)){
            try {
                //code...
                $user = new User();
                if($user->setEmail($_POST['email']) == true){
                    $user->sendResetEmail();
                    exit("Password reset email sent!");
                }
                else{
                    echo "User does not exist!";
                }
            } catch (\Throwable $th) {
                //throw $th; 
                $nomail = $th->getMessage();
            }
        }
    }



?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
            <h1>Send email</h1>
            <ul>
                <li><input type="text" name="email" placeholder="email" required></li>
                <li><input type="submit" value="send Email" name="btn"></li>
            </ul>
            
            <?php if(isset($nomail)): ?>
                <div> <?php echo $nomail ?></div>        
            <?php endif; ?>

    </form>
</body>
</html>