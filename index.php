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

    $limit = 6; // number of prompts to display per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // current page number
    $offset = ($page - 1) * $limit;

    $prompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query);
    


    // count the total number of prompts with the selected filter
    $totalPrompts = count(Prompt::countAllVerifiedPrompts($search_query));

    $totalPages = ceil($totalPrompts / $limit);

    //getting categories from database
    $allCategories = Prompt::getCategories();

    //setting up image getting
    $image = new Image();
    $url = $image->getUrl();
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
    <h1 class=" text-[#0464A4] text-5xl my-10 flex justify-center">Prompt marketplace</h1>
    <div class="flex justify-center items-center">
    <form method="get" class="mr-2">
        <input type="text" name="search_query" class="p-3 border-2 rounded-lg border-[#0464A4] w-96" placeholder="Search">
        <button type="submit" name="submit" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-3 px-8 rounded-lg mx-4 cursor-pointer">Search</button>
    </form>
    <form action="" method="post" id="categoryFilter">
            <select onchange="document.getElementById('categoryFilter').submit();" name="dropdown" id="dropdown" required>
                <option value="" disabled selected>Select a category</option>
                <option value="*">All categories</option>
                <?php foreach($allCategories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                <?php endforeach; ?>
                
            </select>
                    
            <?php if(isset($_POST['dropdown'])): ?>
                <?php if($_POST['dropdown'] == '*'): ?>
                    <?php $prompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query); ?>
                <?php else: ?>
                    <?php $selectedCategory = $_POST['dropdown']; ?>
                    <?php $prompts = Prompt::getPromptsByCategory($selectedCategory); ?>
                <?php endif; ?>
            <?php endif; ?>

        </form> 
</div>

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
                <?php $promptprice = Prompt::getPromptprice($prompt['price_id']); ?>
                <div class="bg-white p-10 rounded-3xl">
                    <ul class="list-none flex flex-col">
                        <div class="flex flex-row justify-between mb-5">
                        <li class="text-lg flex"><a id="likebtn" data-postid="<?php echo $prompt["id"]; ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#C8C8CC" width="24" height="24">
                        <path d="M12 20.934l-1.414-1.414C5.045 14.319 2 11.238 2 7.5 2 4.364 4.364 2 7.5 2c1.899 0 3.728.929 4.854 2.475C13.772 2.929 15.601 2 17.5 2 20.636 2 23 4.364 23 7.5c0 3.738-3.045 6.819-8.586 12.02L12 20.934z"/>
                        </svg>
                        </a><p><?php echo Like::getLikes($prompt["id"])?></p></li>
                        <li class="text-xl flex"><p><?php echo $prompt["title"] ?></p></li>
                        <li class="text-lg flex"><a href="userprofile.php?user=<?php echo $prompt['user_id'] ?>"><?php echo $promptUser["username"] ?></a></li>
                        </div>
                        <?php if(!empty($_SESSION["userid"])): ?>
                            <li><img  class="rounded-3xl" src="<?php echo $url.$prompt["photo_url"]?>" alt="Prompt photo"></li>
                        <?php else: ?>
                            <li><img class="blur-lg rounded-3xl w-15 h-15" src="<?php echo $url.$prompt["photo_url"]?>" alt="Prompt photo"></li>
                        <?php endif; ?>
                        <li class="mt-5"><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <li><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <!-- shouldnt be visible before buying -->
                        <?php if(isset($_SESSION["loggedin"])): ?>
                        <?php if(count(Prompt::checkBought($prompt['id'])) >=1 ):?>
                            <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                            <li class="mb-5"><p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p></li>
                        <?php else: ?>
                            <li><p><b>Price: </b><?php echo $promptprice["price"] ?> Credits</p></li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <?php if(isset($_SESSION["userid"])): ?>
                            <?php if($_SESSION['userid'] == $prompt['user_id']): ?>
                            <?php else: ?>
                                <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center" id="buybtnid" data-postid="<?php echo $prompt["id"]?>"><button><?php if(count(Prompt::checkBought($prompt['id'])) >=1 ){ echo "Bought";} else { echo "Buy";} ?></button></li>
                            <?php endif; ?>
                        <?php endif ?>
                        <!-- if username is logged in show this button  -->
                        <?php if(isset($_SESSION["username"])):?>
                            <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center" style="margin-top: 10px;margin-bottom:10px;"><button class="btnTest" id="btnFavorites" data-postid=<?php echo $prompt["id"] ?>><?php if(count(Prompt::checkFavorite($prompt['id'])) >=1 ){ echo "Remove from favorites";} else { echo "Add to favorites";} ?></button></li>
                            <?php if($prompt["user_id"] == $_SESSION["userid"]):?>
                                <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><a href="deletepost.action.php?pid=<?php echo $prompt["id"] ?>&uid=<?php echo $prompt["user_id"] ?>">Delete</a></li>
                            <?php else: ?>
                                <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><button class="reportbtn" id="reportbtnid" data-postid="<?php echo $prompt["id"]?>"><?php if(count(Prompt::checkReport($prompt['id'])) >=1 ){ echo "Reported";} else { echo "Report";} ?></button></li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(isset($_SESSION["admin"])):?>
                            <?php if($_SESSION['admin'] == true): ?>
                                <?php if($_SESSION["admin"] == true):?>
                                    <p style="margin-top: 30px;"><b>Moderation:</b></p>
                                    <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
    <?php endforeach;?>
        </div>
    

        <?php if ($totalPages > 1) : ?>
            <div class="flex items-center justify-center my-8 ">
                <?php if ($page > 1) : ?>
                <a href="index.php?page=<?php echo $page - 1 ?>" class="px-3 py-2 bg-[#0464A4] hover:bg-[#0242A2] text-white rounded-l-md">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="index.php?page=<?php echo $i ?>" class="px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 <?php if ($i === $page) echo 'text-black font-bold'; ?>"><?php echo $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                <a href="index.php?page=<?php echo $page + 1 ?>" class="px-3 py-2 bg-[#0464A4] hover:bg-[#0242A2] text-white rounded-r-md">Next</a>
                <?php endif; ?>
            </div>
        <?php endif;} ?>
        <script>
            let likebtn = document.querySelectorAll("#likebtn");
            likebtn.forEach(function (btn) {
                btn.addEventListener("click", function () {
                    let currentBtn = this;
                    //get 
                    let postId = this.dataset.postid;
                    let siblingP = currentBtn.nextElementSibling;
                    let heartIcon = currentBtn.querySelector("svg");
                    //post naar database
                    let formData = new FormData();
                    formData.append("post_id", postId);
                    fetch("ajax/like.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(function(response){
                        return response.json();
                    })
                    .then(function(json){
                        siblingP.innerHTML = json.likes;
                        if (json.status == 'Unlike') {
                            heartIcon.setAttribute('fill', '#0464A4');
                        }
                        else {
                            heartIcon.setAttribute('fill', '#C8C8CC');
                        }
                    });

                });
            });
        </script>
        <script>
    let promptsID = document.querySelectorAll("#btnFavorites");
    promptsID.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let currentBtn = this;
            let postId = this.dataset.postid;
            //post naar database

            let formData = new FormData();
            formData.append("post_id", postId);

            fetch("ajax/saveFavorite.php", {
                method: "POST",
                body: formData
            })
            .then(function(response){
                return response.json();
            })
            .then(function(json){
                if (json.status == 'success') {
                    currentBtn.innerHTML = json.message;
                   
                    
                }

            });

        });
    });

    let reportid = document.querySelectorAll("#reportbtnid");
    reportid.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let currentBtn = this;
            let postId = this.dataset.postid;
            //post naar database
            let formData = new FormData();
            formData.append("post_id", postId);

            fetch("ajax/report.php", {
                method: "POST",
                body: formData
            })
            .then(function(response){
                return response.json();
            })
            .then(function(json){
                if (json.status == 'success') {
                    currentBtn.innerHTML = json.message;
                }

            });

        });
    });

    let buyid = document.querySelectorAll("#buybtnid");
    buyid.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let currentBtn = this;
            let postId = this.dataset.postid;
            //post naar database
            let formData = new FormData();
            formData.append("post_id", postId);
            fetch("ajax/buypost.php", {
                method: "POST",
                body: formData
            })
            .then(function(response){
                return response.json();
            })
            .then(function(json){
                if (json.status == 'success') {
                    currentBtn.innerHTML = json.message;
                }

            });

        });
    });
</script>
</body>
</html>