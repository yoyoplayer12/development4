<?php
        include_once(__DIR__ . "/bootstrap.php");
        include_once(__DIR__ . "/classes/Prompt.php");



        //logindetection
        if(isset($_SESSION["loggedin"])) {
            $getUser = User::getSessionUser();
        }
        else {
            header("Location: login.php");
        }

        
        $printFavorites = Prompt::getFavorites();
        // var_dump($printFavorites[0]['postId']);
        
        
    
        //remove favorites after click on button
        if(isset($_POST['removeFav'])){
            var_dump($printFavorites[0]['postId']);
        
            $promptId = $printFavorites[0]['postId'];
            //var_dump($promptId);
            $test = Prompt::deleteFavorite($promptId);
            
            header("Location: profile.php");
        }
        



        
        
        

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
    <title>Eve - <?php echo $_SESSION['username']?>'s profile</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <?php if(isset($_SESSION['loggedin'])):?>
        <h1><?php echo $_SESSION['username']?>'s profile</h1>
        <p class="profile-bio">Bio: <?php echo $getUser["bio"];?></p>
        <img src="<?php echo $getUser["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 object-cover">

        <p>my favorite prompts:</p>


        <?php foreach($printFavorites as $printFavorite): ?>
        <div class="bg-white p-10 rounded-3xl">
                    <ul class="list-none flex flex-col">
                        <li class="text-xl flex justify-center inline-block"><p><?php echo $printFavorite["title"] ?></p></li>
                        <li class="text-lg flex justify-end inline-block "><a href="userprofile.php?user=<?php echo $printFavorite['user_id'] ?>"></a></li>
                        <?php $promptUser = Prompt::getPromptUser($printFavorite['user_id']); ?>
                        <?php $promptCat = Prompt::getPromptCat($printFavorite['cat_id']); ?>
                        <!-- <?php $promptNumber = Prompt::deleteFavorite($printFavorite['id']);?> -->
                        <!-- <?php var_dump($promptNumber) ?> -->


                        <?php if(!empty($_SESSION["userid"])): ?>
                            <li><img  class="rounded-3xl" src="<?php echo $printFavorite["photo_url"]?>" alt="Prompt photo"></li>
                        <?php else: ?>
                            <li><img class="blur-lg rounded-3xl w-15 h-15" src="<?php echo $printFavorite["photo_url"]?>" alt="Prompt photo"></li>
                        <?php endif; ?>

                        <li><p><b>Description: </b><?php echo $printFavorite["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $printFavorite["postdate"] ?></p></li>
                        <!-- shouldnt be visible before buying -->
                        <li><p><b>Prompt: </b><?php echo $printFavorite["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $printFavorite["prompt_info"] ?></p></li>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <li><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <li><button>Buy</button></li>

                        
                        
                          <!-- make button that removes the prompt from favorites with the Prompt class -->
                          <!-- <li><button class="btnTest" id="btnFavorites" data-postid=<?php echo $printFavorite["id"] ?> data-usernameid=<?php echo $_SESSION["username"];?>  ><?php if(Prompt::deleteFavorite($printFavorite['id'])){ echo "remove from favorites";} ?></button></li> -->
                          <form method="POST" action="profile.php">
                            <li>
                                <input type="submit" value="Remove from favorites" name="removeFav"> </input>
                            </li>
                          </form>

                        
                           
                        
                       

                        
                    </ul>
                </div>
            <?php endforeach; ?>
        

        <a href="editprofile.php">Edit profile</a>
        <a href="changePassword.php">Change password</a>
        <a href="logout.php">Log out</a>
        <a href="deleteprofile.action.php">Delete profile</a>

    <?php else:?>
        <h1>Log in to see your profile</h1>
        <a href="logout.php">Log in</a>

    <?php endif;?>
</body>
</html>
