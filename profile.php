<?php
include_once(__DIR__ . "/bootstrap.php");
//logindetection
if (isset($_SESSION["loggedin"])) {
    $getUser = User::getSessionUser();
} else {
    header("Location: login.php");
}
//setting up image getting
$image = new Image();
$url = $image->getUrl();
$printFavorites = Prompt::getFavorites();
$boughtpromptids = Prompt::getBoughtPromptIds();
if ($boughtpromptids != false) {
    $boughtprompts = Prompt::getBoughtPrompts($boughtpromptids['prompt_id']);
} else {
    $boughtprompts = [];
}
//remove favorites after click on button
if (isset($_POST['removeFav'])) {
    $promptId = $printFavorites[0]['postId'];
    $test = Prompt::deleteFavorite($promptId);
    header("Location: profile.php");
}

$followedUsers = User::getFollowedCategories();

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
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <title>Eve - <?php echo $_SESSION['username'] ?>'s profile</title>
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <div class="flex justify-center items-center mt-20">
        <div>
            <?php if (isset($_SESSION['loggedin'])) : ?>
                <div class=" w-90 rounded-3xl bg-white px-10 py-10 ">
                    <h1 class="text-2xl font-bold text-center"><?php echo $_SESSION['username'] ?>'s profile</h1>
                    <div class="flex flex-col items-center my-4">
                        <img src="<?php echo $url . $getUser["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 object-cover m-10">
                        <p class="text-gray-700"><?php echo $getUser["bio"]; ?></p>
                    </div>
                    <div class="flex justify-center">
                        <a href="editprofile.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Edit profile</a>
                        <a href="changePassword.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Change password</a>
                        <a href="logout.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Log out</a>
                        <a href="deleteprofile.action.php?uid=<?php echo $_SESSION['userid']; ?>" class="mx-2 px-4 py-2 rounded-md bg-red-500 hover:bg-red-600 text-white">Delete profile</a>
                    </div>
                </div>
                <div>
                </div>
                <div>
                    <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Favorite prompts</h2>
                    <?php foreach ($printFavorites as $printFavorite) : ?>
                        <?php $promptUser = Prompt::getPromptUser($printFavorite['user_id']); ?>
                        <?php $promptCat = Prompt::getPromptCat($printFavorite['cat_id']); ?>
                        <div class="bg-white px-10 rounded-3xl flex justify-center items-center flex-col py-5 mb-10">
                            <p class="text-[#0464A4] text-xl"><?php echo $printFavorite["title"] ?></p>
                            <ul class="flex flex-row gap-10 mb-5 pt-5">
                                <li>
                                    <p><?php echo $promptUser['username']; ?></p>
                                </li>
                                <li>
                                    <p class="text-[#0464A4]"><?php echo $promptCat["category"] ?></p>
                                </li>
                            </ul>
                            <ul>
                                <li><img class="rounded-3xl w-80 mb-5" src="<?php echo $url . $printFavorite["photo_url"] ?>" alt="Prompt photo"></li>
                                <li>
                                    <p><b>Description: </b><?php echo $printFavorite["description"] ?></p>
                                </li>
                                <li>
                                    <p><b>Postdate: </b><?php echo $printFavorite["postdate"] ?></p>
                                </li>
                                <!-- shouldnt be visible before buying -->
                                <li>
                                    <p><b>Prompt: </b><?php echo $printFavorite["prompt"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt description: </b><?php echo $printFavorite["prompt_info"] ?></p>
                                </li>
                                <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                                <li>
                                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                                </li>
                                <div class="mt-5 flex flex-col gap-5 pb-5">
                                    <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><button>Buy</button></li>
                                    <!-- make button that removes the prompt from favorites with the Prompt class -->
                                    <form method="POST" action="profile.php">
                                        <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center">
                                            <input type="submit" value="Remove from favorites" name="removeFav"> </input>
                                        </li>
                                    </form>
                                </div>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                    <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Bought prompts</h2>
                    <?php foreach ($boughtprompts as $prompt) : ?>
                        <div class="bg-white px-10 rounded-3xl flex justify-center items-center flex-col py-5 mb-10">
                            <p class="text-[#0464A4] text-xl"><?php echo $prompt["title"] ?></p>
                            <ul class="flex flex-row gap-10 mb-5 pt-5">
                                <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                                <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                                <li>
                                    <p class="text-[#0464A4]"><?php echo $promptUser['username'] ?></p>
                                </li>
                                <li>
                                    <p class="text-[#0464A4]"><?php echo $promptCat["category"] ?></p>
                                </li>
                            </ul>
                            <ul class="flex flex-row gap-10 mb-5 pt-5">

                                <li class="text-lg flex justify-end inline-block "><a href="userprofile.php?user=<?php echo $prompt['user_id'] ?>"></a></li>

                                <li>
                                    <p><?php echo $promptUser['username']; ?></p>
                                </li>
                            </ul>
                            <ul>
                                <li><img class="rounded-3xl w-80 mb-5" src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                                <li>
                                    <p><b>Description: </b><?php echo $prompt["description"] ?></p>
                                </li>
                                <li>
                                    <p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p>
                                </li>
                                <!-- shouldnt be visible before buying -->
                                <li>
                                    <p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p>
                                </li>
                                <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                                <li>
                                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                                </li>
                                <div class="mt-5 flex flex-col gap-5 pb-5">
                                    <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><button>Buy</button></li>
                                    <!-- make button that removes the prompt from favorites with the Prompt class -->
                                    <form method="POST" action="profile.php">
                                        <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center">
                                            <input type="submit" value="Remove from favorites" name="removeFav"> </input>
                                        </li>
                                    </form>
                                </div>
                            </ul>
                        </div>
                    <?php endforeach; ?>




                    <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Prompts from followed users</h2>
                    <?php if (empty($followedUsers)) : ?>
                        <?php echo "<h1 class='noposts'>No followed users yet...</h1>"; ?>
                    <?php else : ?>
                        <?php foreach ($followedUsers as $followedUser) : ?>

                            <div class="bg-white px-10 rounded-3xl flex justify-center items-center flex-col py-5 mb-10">
                                <p class="text-[#0464A4] text-xl"><?php echo $followedUser["title"] ?></p>
                                <ul class="flex flex-row gap-10 mb-5 pt-5">
                                    <li>
                                        <p><?php echo $followedUser['username']; ?></p>
                                    </li>
                                    <li>
                                        <p class="text-[#0464A4]"><?php echo $followedUser["category"] ?></p>
                                    </li>
                                </ul>
                                <ul>
                                    <li><img class="rounded-3xl w-80 mb-5" src="<?php echo $url . $followedUser["photo_url"] ?>" alt="Prompt photo"></li>
                                    <li>
                                        <p><b>Description: </b><?php echo $followedUser["description"] ?></p>
                                    </li>
                                    <li>
                                        <p><b>Postdate: </b><?php echo $followedUser["postdate"] ?></p>
                                    </li>
                                    <!-- shouldnt be visible before buying -->
                                    <li>
                                        <p><b>Prompt: </b><?php echo $followedUser["prompt"] ?></p>
                                    </li>
                                    <li>
                                        <p><b>Prompt description: </b><?php echo $followedUser["prompt_info"] ?></p>
                                    </li>
                                    <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                                    <li>
                                        <p><b>Category: </b><?php echo $followedUser["category"] ?></p>
                                    </li>
                                    <div class="mt-5 flex flex-col gap-5 pb-5">
                                        <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><button>Buy</button></li>
                                        <!-- make button that removes the prompt from favorites with the Prompt class -->

                                    </div>

                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>





                </div>
            <?php else : ?>
                <h1 class="text-2xl font-bold">Log in to see your profile</h1>
                <a href="logout.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Log in</a>
            <?php endif; ?>
        </div>
    </div>
</body>


</html>