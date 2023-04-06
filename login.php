<?php
    include_once(__DIR__ . "/bootstrap.php");
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
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <div class="loginform">
        <form action="" method="post">
            <h1>Log in</h1>
            <ul>
                <li><input type="text" name="email" placeholder="Email" required></li>
                <li><input type="password" name="password" placeholder="Password" required></li>
                <li><input type="submit" value="Log in"></li>
                <li><a href="register.php">Create an account</a></li>
                <li class="warningtext"><?php echo $loginwarning ?></li>
            </ul>
        </form>
    </div>

</body>
</html>
