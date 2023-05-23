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
    $rejectedprompts = [];
    $reportedprompts = [];
    $rejectedpromptscount = 0;
    $unverifiedpromptscount = 0;
    $reportedpromptscount = 0;
    $unverifiedprompts = Prompt::getUnverifiedPrompts();
    $rejectedprompts = Prompt::getRejectedPrompts();
    $reportedprompts = Prompt::getReportedPrompts();
    $rejectedpromptscount = count($rejectedprompts);
    $unverifiedpromptscount = count($unverifiedprompts);
    $reportedpromptscount = count($reportedprompts);
    
    //setting up image getting
    $image = new Image();
    $url = $image->getUrl()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Eve - Admin Panel</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1>Admin panel</h1>
    <h1><?php echo $unverifiedpromptscount?> To verify</h1>
    <?php 
        if (empty($unverifiedprompts)) {
            echo "<h1 class='noposts'>Congrats, you verified them all!</h1>";
        }
        else{
            foreach($unverifiedprompts as $prompt): ?>
                <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                <?php $promptprice = Prompt::getPromptprice($prompt['price_id']); ?>
                <div class="prompt">
                    <ul>
                        <li><p><b>Title: </b><?php echo $prompt["title"] ?></p></li>
                        <li><p><b>User: </b><?php echo $promptUser['username'] ?></p></li>
                        <li><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <li><p><b>Price: </b><?php echo $promptprice["price"] ?></p></li>
                        <li><img src="<?php echo $url.$prompt["photo_url"]?>" alt="Prompt photo"></li>
                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p></li>
                        <!-- Hier komt de verify button ==> if verify = 0 ==> andere backgroundcolor en text -->
                        <a href="verify.action.php?id=<?php echo $prompt["id"] ?>">Approve</a>
                        <a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a>
                    </ul>
                </div>
    <?php endforeach;} ?>
    <h1><?php echo $rejectedpromptscount?> Rejected</h1>
    <?php 
        if (empty($rejectedprompts)) {
            echo "<h1 class='noposts'>No rejected posts yet...</h1>";
        }
        else{
            foreach($rejectedprompts as $prompt): ?>
                <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>  
                <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                <div class="prompt">
                    <ul>
                        <li><p><b>Title: </b><?php echo $prompt["title"] ?></p></li>
                        <li><p><b>User: </b><?php echo $promptUser['username'] ?></p></li>
                        <li><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <li><img src="<?php echo $url.$prompt["photo_url"]?>" alt="Prompt photo"></li>
                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p></li>
                    </ul>
                </div>
    <?php endforeach;} ?>
    <h1><?php echo $reportedpromptscount?> Reported</h1>
    <?php 
        if (empty($reportedprompts)) {
            echo "<h1 class='noposts'>No reported posts yet...</h1>";
        }
        else{
            foreach($reportedprompts as $reportedprompt): ?>
            <?php $prompt = Prompt::getPromptById($reportedprompt['prompt_id']) ?>
                <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                <div class="prompt">
                    <ul>
                        <li><p><b>Title: </b><?php echo $prompt["title"] ?></p></li>
                        <li><p><b>Report count: </b><?php echo $reportedprompt["count"] ?></p></li>
                        <li><p><b>User: </b><?php echo $promptUser['username'] ?></p></li>
                        <li><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <li><img src="<?php echo $url.$prompt["photo_url"]?>" alt="Prompt photo"></li>
                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p></li>
                    </ul>
                </div>
    <?php endforeach;} ?>
</body>
</html>