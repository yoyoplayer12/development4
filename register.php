<?php 


include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/nav.php");

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
    <title>Eve - Sign up</title>
    <link href="normalize.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="form flex flex-row justify-center items-center">
		<div class="w-1/2 h-screen flex justify-center items-center">
			<form action="" method="post">
            <img  src="assets/logo.png" class="flex justify-center w-50 ml-10 mb-10">
				<h1 class="text-[#0464A4] text-5xl mb-20">Sign Up</h1>
				<div class="">
					<input type="text" name="email" placeholder="Email" class="border-2 py-2 flex w-full justify-center rounded-md mb-5">
                    <?php if (isset($emailError)) : ?>
                        <p><?php echo $emailError; ?></p>
                    <?php endif; ?>
				</div>
                <div class="">
					<input type="text" name="username" placeholder="Username" class="border-2 py-2 flex w-full justify-center rounded-md mb-5">
                    <?php if (isset($usernameError)) : ?>
                        <p><?php echo $usernameError; ?></p>
                    <?php endif; ?>
				</div>
				<div class="">
					<input type="password" name="password" placeholder="Password" class="border-2 py-2 flex w-full justify-center rounded-md mb-10">
                    <?php if (isset($passwordError)) : ?>
                        <p><?php echo $passwordError; ?></p>
                    <?php endif; ?>
				</div>

				<div class="">
					<input type="submit" value="Sign up" name="registerBtn" class="flex w-full justify-center mb-5 rounded-md bg-[#0464A4] hover:bg-[#0444A4] py-3 text-sm font-semibold text-white cursor-pointer">
				</div>
                <p>already have an account? <a href="login.php" class="text-[#0464A4]">Log in</a></p> 
			</form>
           
		</div>
        <div class="w-1/2">
        <img  src="images/background.jpg" class="w-full h-screen">
        </div>
	</div>
</body>
</html>