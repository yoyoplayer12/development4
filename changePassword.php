<?php 

    include_once(__DIR__ . "/bootstrap.php");
    include_once(__DIR__ . "/classes/User.php");
    include_once(__DIR__ . "/classes/Db.php");

   

    if(!empty($_POST)){
        if(!empty($_POST['currentPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newPassword2'])){
            
            $user = new User();
            $user->comparePassword();
            
            if($user->comparePassword() == true && $_POST['newPassword'] == $_POST['newPassword2']){
                $user->setPassword($_POST['newPassword']);
                $user->updatePassword();
                $success = "Password changed successfully";
            }
            else{
                $error2 = "Current password is incorrect or new passwords don't match";
            }

        }
        else{
            $error = "Please fill in all fields";
        }
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
</head>
<body>
    <form action="" method="post">

    <h2>Change password from <?php echo $_SESSION['username']?>'s account</h2> 

        <ul>
            <li><input name ="currentPassword" type="password" placeholder="current password"></li>
            <li><input name ="newPassword" type="password" placeholder="new password"></li>
            <li><input name ="newPassword2" type="password" placeholder="confirm new password"></li>
            <li><input type="submit" value="change password" name="changePassword"></li>

            <?php if(isset($error)): ?>
                <div> <?php echo $error ?></div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div> <?php echo $success ?></div>
            <?php endif; ?>

            <?php if(isset($error2)): ?>
                <div> <?php echo $error2 ?></div>
            <?php endif; ?>


        </ul>

        
        





    </form>
</body>
</html>