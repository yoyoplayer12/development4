<?php
    include_once(__DIR__ . "/bootstrap.php");
    include_once(__DIR__ . "/classes/Db.php");
    //logindetection
    $prompts = [];

    if (!empty($_GET['search_query'])) {
        $search_query = $_GET['search_query'];
    } else {
        $search_query = "";
    }

    $limit = 4; // number of prompts to display per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // current page number
    $offset = ($page - 1) * $limit;

    $prompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query);

    // count the total number of prompts with the selected filter
    $totalPrompts = count(Prompt::countAllVerifiedPrompts($search_query));

    $totalPages = ceil($totalPrompts / $limit);

    //getting categories from database
    $allCategories = Prompt::getCategories();




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
    <title>Eve - Home</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1>Prompt marketplace</h1>
    <form method="get">
		<input type="text" name="search_query">
		<input type="submit" name="submit" value="Search">
	</form>

    <form action="" method="post" id="categoryFilter">
            <select onchange="document.getElementById('categoryFilter').submit();" name="dropdown" id="dropdown" required>
                <option value="" disabled selected>Choose a category</option>
                <?php foreach($allCategories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                <?php endforeach; ?>
                
            </select>
                    
            <?php if(isset($_POST['dropdown'])): ?>
                <?php $selectedCategory = $_POST['dropdown']; ?>
                <?php $prompts = Prompt::getPromptsByCategory($selectedCategory); ?>
            <?php else: ?>
                <?php $prompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query); ?>
            <?php endif; ?>

        </form> 


        <div class="mx-auto mt-5 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-8 sm:mt-5 sm:pt-16 lg:mx-10 lg:max-w-none lg:grid-cols-3">
        <?php if(isset($notPrompt)): ?>
        <p><?php echo $notPrompt ?></p>
    <?php endif; ?>
    <?php 
        if (empty($prompts)) {
            echo "<h1 class='noposts'>There are no prompts right now, try again later!</h1>";
        }
        else{
            foreach($prompts as $prompt): ?>
                <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                <div class="bg-white p-10 rounded-3xl">
                    <ul class="list-none flex flex-col">
                        <li class="text-xl flex justify-center inline-block"><p><?php echo $prompt["title"] ?></p></li>
                        <li class="text-lg flex justify-end inline-block "><a href="userprofile.php?user=<?php echo $prompt['user_id'] ?>"><?php echo $promptUser['username'] ?></a></li>

                        <?php if(!empty($_SESSION["userid"])): ?>
                            <li><img  class="rounded-3xl" src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                        <?php else: ?>
                            <li><img class="blur-lg rounded-3xl w-15 h-15" src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                        <?php endif; ?>

                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <!-- shouldnt be visible before buying -->
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt-info"] ?></p></li>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <li><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <li><button>Buy</button></li>


                        <li><button class="btnTest" id="btnFavorites" data-postid=<?php echo $prompt["id"] ?>>Add to favorites</button></li>
                        <!-- <?php var_dump($prompt["id"]); ?> -->


                        <?php if(isset($_SESSION["admin"])):?>
                            <?php if($_SESSION["admin"] == true):?>
                                <li><a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a></li>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if(isset($_SESSION["userid"])):?>
                            <?php if($prompt["user_id"] == $_SESSION["userid"]):?>
                                <li><a href="deletepost.action.php?pid=<?php echo $prompt["id"] ?>&uid=<?php echo $prompt["user_id"] ?>">Delete</a></li>
                            <?php endif ?>
                        <?php endif ?>
                    </ul>
                </div>
    <?php endforeach;} ?>
        </div>
    

    <?php if ($totalPages > 1) : ?>
            <div class="pagination text-black">
                <?php if ($page > 1) : ?>
                    <a href="index.php?page=<?php echo $page - 1 ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="index.php?page=<?php echo $i ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="index.php?page=<?php echo $page + 1 ?>" >Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <script src="favorites.js"></script>
</body>
</html>