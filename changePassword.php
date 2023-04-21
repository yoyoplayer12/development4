<?php 

    include_once(__DIR__ . "/bootstrap.php");

    if(!empty($_POST)){
        if(!empty($_POST['currentPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newPassword2'])){
            
            try {
                //code...
            } catch (\Throwable $th) {
                //throw $th;
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
            <li><input type="text" name ="currentPassword" placeholder="current password"></li>
            <li><input type="text" name ="newPassword" placeholder="new password"></li>
            <li><input type="text" name ="newPassword2" placeholder="confirm new password"></li>
            <li><input type="submit" value="change password" name="changePassword"></li>

            <?php if(isset($error)): ?>
                <div> <?php echo $error ?></div>
            <?php endif; ?>

        </ul>

        
        





    </form>
</body>
</html>