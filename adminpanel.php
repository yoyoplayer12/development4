<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    if(isset($_SESSION["admin"])) {
        if($_SESSION["admin"] == true){
            //continue
        }
        else{
            header("Location: index.php");
        }
    }
    else {
        header("Location: index.php");
    }
    $unverifiedprompts = [];
    $unverifiedprompts = Prompt::getUnverifiedPrompt();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Eve - Admin Panel</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1>Admin panel</h1>
    <h1>To verify</h1>
    <?php 
        if (empty($unverifiedprompts)) {
            echo "<h1 class='noposts'>Congrats, you verified them all!</h1>";
        }
        else{
            foreach($unverifiedprompts as $prompt): ?>
                <div class="prompt">
                    <ul>
                        <li><p><b>Title: </b><?php echo $prompt["title"] ?></p></li>
                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><img src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt-description"] ?></p></li>
                        <!-- Hier komt de verify button ==> if verify = 0 ==> andere backgroundcolor en text -->
                        <?php if($prompt["verified"] == 0):?>
                            <a href="verify.php?id=<?php echo $prompt["id"] ?>">Approve</a>
                        <?php endif; ?>
                    </ul>
                </div>
    <?php endforeach;} ?>
</body>
</html>