<?php 

require_once 'vendor/autoload.php';
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Db.php");

$config = parse_ini_file('config/config.ini', true);
$key = $config['keys']['SENDGRID_API_KEY'];
apache_setenv('SENDGRID_API_KEY', $key);
 
if(isset($_POST['registerBtn'])){
    
    if(!empty($_POST)){
        try {
            $user = new User();
            try {
            $user->setEmail($_POST['email']);
            } 
            catch (\Throwable $th) {
                $emailError = $th->getMessage();
            }
            try {
            $user->setUsername($_POST['username']);
            }
            catch (\Throwable $th) {
                $usernameError = $th->getMessage();
            }
            try {
            $user->setPassword($_POST['password']);
            }
            catch (\Throwable $th) {
                $passwordError = $th->getMessage();
            }
            
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            $user->setToken($token);
            $user->save();
            $user->sendConfirmEmail();


        
            header("Location: index.php");
        }
        catch (\Throwable $th) {
            $error = $th->getMessage();
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
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com/%22%3E"></script>
    <link href="normalize.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="">
		<div class="">
			<form action="" method="post">
				<h2 form__title>Sign Up</h2>

				<div class="">
					<label for="Email">Email</label>
					<input type="text" name="email">
                    <?php if (isset($emailError)) : ?>
                        <p><?php echo $emailError; ?></p>
                    <?php endif; ?>
				</div>
                <div class="">
					<label for="Username">Username</label>
					<input type="text" name="username">
                    <?php if (isset($usernameError)) : ?>
                        <p><?php echo $usernameError; ?></p>
                    <?php endif; ?>
				</div>
				<div class="">
					<label for="Password">Password</label>
					<input type="password" name="password">
                    <?php if (isset($passwordError)) : ?>
                        <p><?php echo $passwordError; ?></p>
                    <?php endif; ?>
				</div>

				<div class="">
					<input type="submit" value="Sign up" name="registerBtn" class="">
				</div>
			</form>
		</div>
	</div>
</body>
</html>