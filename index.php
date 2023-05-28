<?php
include_once(__DIR__ . "/bootstrap.php");
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
$prompts = [];
//filter
$minprice = Prompt::getMinPrice();
$maxprice = Prompt::getMaxPrice();
$minpriceid = Prompt::getPriceIdByPrice($minprice["MIN(price)"]);
$maxpriceid = Prompt::getPriceIdByPrice($maxprice["MAX(price)"]);
$promptids = Prompt::searchByPriceRange($minpriceid['id'], $maxpriceid['id']);
$minpricefilter = [];
$maxpricefilter = [];
$filterlabels = [];
if (isset($_POST['filter'])) {
    $limit = $_POST['limit'];
    $pagefilterlabel = $_POST['limit'] . " posts per page";
    array_push($filterlabels, $pagefilterlabel);
    if ($_POST['minprice'] != null && $_POST['maxprice'] != null) {
        if ($_POST['minprice'] <= $_POST['maxprice']) {
            $minpricefilter = Prompt::getPriceIdByPrice($_POST['minprice']);
            $maxpricefilter = Prompt::getPriceIdByPrice($_POST['maxprice']);
            if ($minpricefilter != false && $maxpricefilter != false) {
                $promptids = Prompt::searchByPriceRange($minpricefilter['id'], $maxpricefilter['id']);
                $pricefilterlabel = "Price: " . $_POST['minprice'] . " to " . $_POST['maxprice'] . " Credits";
                array_push($filterlabels, $pricefilterlabel);
            }
        }
    }
}
if (isset($_POST['reset'])) {
    $promptids = Prompt::searchByPriceRange($minpriceid['id'], $maxpriceid['id']);
    $filterlabels = [];
}
if (isset($_POST['filter'])) {
    $verfiedprompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query);
    foreach ($promptids as $promptid) {
        foreach ($verfiedprompts as $verfiedprompt) {
            if ($promptid['id'] == $verfiedprompt['id']) {
                array_push($prompts, $verfiedprompt);
            }
        }
    }
} else {
    $prompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query);
}
//filter
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
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <title>Eve - Home</title>
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <h1 class="text-[#0464A4] text-5xl my-10 flex justify-center">Prompt marketplace</h1>
    <div class="flex justify-center items-center flex-wrap mb-8">
        <form method="get" class="mr-2 flex justify-center items-center mb-4 sm:mb-0">
            <input type="text" name="search_query" class="p-3 border-2 rounded-lg border-[#0464A4] w-64 sm:w-96" placeholder="Search">
            <button type="submit" name="submit" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-3 px-8 rounded-lg mx-4 cursor-pointer">Search</button>
        </form>
        <form action="" method="post" id="categoryFilter" class="flex justify-center items-center">
            <select onchange="document.getElementById('categoryFilter').submit();" name="dropdown" id="dropdown" required class="bg-[#0464A4] px-5 py-3 rounded-lg text-white cursor-pointer">
                <option value="" disabled selected>Select a category</option>
                <option value="*">All categories</option>
                <?php foreach ($allCategories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($_POST['dropdown'])) : ?>
                <?php if ($_POST['dropdown'] == '*') : ?>
                    <?php $prompts = Prompt::getVerifiedPrompts($limit, $offset, $search_query); ?>
                <?php else : ?>
                    <?php $selectedCategory = $_POST['dropdown']; ?>
                    <?php $prompts = Prompt::getPromptsByCategory($selectedCategory); ?>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
    <div class="mt-10 flex flex-col items-center mb-40">
        <div class="bg-white px-5 py-5 rounded-2xl w-full sm:w-auto">
            <h3 class="mb-5 text-2xl text-[#0464A4] flex justify-center">Filters</h3>
            <form action="" method="post" id="filter" class="flex flex-col sm:flex-row gap-5 justify-center">
                <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                    <label for="price" class="flex justify-center"><b>Price: (<?php echo $minprice["MIN(price)"] ?> to <?php echo $maxprice['MAX(price)'] ?> Credits)</b></label>
                    <div class="flex flex-col sm:flex-row items-center gap-5">
                        <input type="number" class="px-2 py-2 rounded-lg text-center bg-slate-200 mb-2 sm:mb-0" placeholder="Minimum price" name="minprice" value="<?php if (isset($_POST['minprice'])) {
                                                                                                                                                                        echo $_POST['minprice'];
                                                                                                                                                                    } ?>">
                        <input type="number" class="px-2 py-2 rounded-lg text-center bg-slate-200" placeholder="Maximum price" name="maxprice" value="<?php if (isset($_POST['maxprice'])) {
                                                                                                                                                            echo $_POST['maxprice'];
                                                                                                                                                        } ?>">
                    </div>
                </div>
                <div class="flex flex-row items-center gap-5 justify-center">
                    <label for="limit" class="flex justify-center"><b>Posts per page</b></label>
                    <select name="limit" id="limit" class="px-2 py-2 rounded-lg text-center bg-slate-200">
                        <?php if (isset($_POST['limit'])) : ?>
                            <?php if ($_POST['limit'] == 6) : ?>
                                <option value="6" selected="selected">6</option>
                            <?php elseif ($_POST['limit'] == 12) : ?>
                                <option value="12" selected="selected">12</option>
                            <?php elseif ($_POST['limit'] == 18) : ?>
                                <option value="18" selected="selected">18</option>
                            <?php elseif ($_POST['limit'] == 24) : ?>
                                <option value="24" selected="selected">24</option>
                            <?php endif; ?>
                        <?php else : ?>
                            <option value="6" selected="selected">6</option>
                            <option value="12">12</option>
                            <option value="18">18</option>
                            <option value="24">24</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row justify-center mt-5 sm:mt-0 gap-5">
                    <button type="submit" name="filter" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-2 px-4 rounded-lg mx-2 cursor-pointer">Filter</button>
                    <button type="submit" name="reset" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-2 px-4 rounded-lg mx-2 cursor-pointer">Reset</button>
                </div>
            </form>
        </div>

        <?php foreach ($filterlabels as $label) : ?>
            <div>
                <p><?php echo $label ?></p>
            </div>
        <?php endforeach; ?>
    </div>



    <div>
        <?php if (isset($notPrompt)) : ?>
            <p><?php echo $notPrompt ?></p>
        <?php endif; ?>
        <div class="mx-auto mt-5 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-8 sm:mt-20 sm:pt-16 lg:mx-10 lg:max-w-none lg:grid-cols-3">
            <?php
            if (empty($prompts)) {
                echo "<h1 class='noposts'>There are no prompts right now, try again later!</h1>";
            } else {
                foreach ($prompts as $prompt) : ?>
                    <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                    <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                    <?php $promptprice = Prompt::getPromptprice($prompt['price_id']); ?>
                    <div class="bg-white p-10 rounded-3xl">
                        <ul class="list-none flex flex-col">
                            <div class="flex flex-row justify-between mb-5">
                                <?php
                                //settinglikecolor
                                $like = new Like();
                                if ($like->checkLike($prompt['id']) === true) {
                                    $heartcolor = "#0464A4";
                                } elseif ($like->checkLike($prompt['id']) === false) {
                                    $heartcolor = "#C8C8CC";
                                }
                                ?>
                                <li class="text-lg flex"><a id="likebtn" data-postid="<?php echo $prompt["id"]; ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="<?php echo $heartcolor; ?>" width="24" height="24">
                                            <path d="M12 20.934l-1.414-1.414C5.045 14.319 2 11.238 2 7.5 2 4.364 4.364 2 7.5 2c1.899 0 3.728.929 4.854 2.475C13.772 2.929 15.601 2 17.5 2 20.636 2 23 4.364 23 7.5c0 3.738-3.045 6.819-8.586 12.02L12 20.934z" />
                                        </svg>
                                    </a>
                                    <p><?php echo Like::getLikes($prompt["id"]) ?></p>
                                </li>
                                <li class="text-xl flex">
                                    <p><?php echo htmlspecialchars($prompt["title"]) ?></p>
                                </li>
                                <li class="text-lg flex"><a href="userprofile.php?user=<?php echo $prompt['user_id'] ?>"><?php echo htmlspecialchars($promptUser["username"]) ?></a></li>
                            </div>
                            <a href="promptdetails.php?pid=<?php echo $prompt['id'] ?>">
                                <?php if (!empty($_SESSION["userid"])) : ?>
                                    <li><img class="rounded-3xl" src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                                <?php else : ?>
                                    <li><img class="blur-lg rounded-3xl w-15 h-15" src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                                <?php endif; ?>
                                <li class="mt-5">
                                    <p><b>Description: </b><?php echo htmlspecialchars($prompt["description"]) ?></p>
                                </li>
                                <li>
                                    <p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p>
                                </li>
                                <li>
                                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                                </li>
                                <!-- shouldnt be visible before buying -->
                                <?php if (isset($_SESSION["loggedin"])) : ?>
                                    <?php if (count(Prompt::checkBought($prompt['id'])) >= 1) : ?>
                                        <li>
                                            <p><b>Prompt: </b><?php echo htmlspecialchars($prompt["prompt"]) ?></p>
                                        </li>
                                        <li class="mb-5">
                                            <p><b>Prompt description: </b><?php echo htmlspecialchars($prompt["prompt_info"]) ?></p>
                                        </li>
                                    <?php else : ?>
                                        <li>
                                            <p><b>Price: </b><?php echo $promptprice["price"] ?> Credits</p>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                                <?php if (isset($_SESSION["userid"])) : ?>
                                    <?php if ($_SESSION['userid'] == $prompt['user_id']) : ?>
                                    <?php else : ?>
                                        <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center" id="buybtnid" data-postid="<?php echo $prompt["id"] ?>" data-postuserid="<?php echo $prompt["user_id"] ?>"><button><?php if (count(Prompt::checkBought($prompt['id'])) >= 1) {
                                                                                                                                                                                                                                                                                        echo "Bought";
                                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                                        echo "Buy";
                                                                                                                                                                                                                                                                                    } ?></button></li>
                                    <?php endif; ?>
                                <?php endif ?>
                                <!-- if username is logged in show this button  -->
                                <?php if (isset($_SESSION["username"])) : ?>
                                    <li class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center" style="margin-top: 10px;margin-bottom:10px;"><button class="btnTest" id="btnFavorites" data-postid=<?php echo $prompt["id"] ?>><?php if (count(Prompt::checkFavorite($prompt['id'])) >= 1) {
                                                                                                                                                                                                                                                                                                echo "Remove from favorites";
                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                                echo "Add to favorites";
                                                                                                                                                                                                                                                                                            } ?></button></li>
                                    <?php if ($prompt["user_id"] == $_SESSION["userid"]) : ?>
                                        <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><a href="deletepost.action.php?pid=<?php echo $prompt["id"] ?>&uid=<?php echo $prompt["user_id"] ?>">Delete</a></li>
                                    <?php else : ?>
                                        <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><button class="reportbtn" id="reportbtnid" data-postid="<?php echo $prompt["id"] ?>"><?php if (count(Prompt::checkReport($prompt['id'])) >= 1) {
                                                                                                                                                                                                                                                        echo "Reported";
                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                        echo "Report";
                                                                                                                                                                                                                                                    } ?></button></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["admin"])) : ?>
                                    <?php if ($_SESSION['admin'] == true) : ?>
                                        <?php if ($_SESSION["admin"] == true) : ?>
                                            <p style="margin-top: 30px;"><b>Moderation:</b></p>
                                            <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </a>
                        </ul>
                    </div>

                <?php endforeach; ?>
            <?php } ?>
        </div>
    </div>
    <?php if ($totalPages > 1) : ?>
        <div class="flex items-center justify-center my-8 ">
            <?php if ($page > 1) : ?>
                <a href="index.php?page=<?php echo $page - 1 ?>" class="px-3 py-2 bg-[#0464A4] hover:bg-[#0242A2] text-white rounded-l-md">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="index.php?page=<?php echo $i ?>" class="px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 <?php if ($i === $page) {
                                                                                                                            echo 'text-black font-bold';
                                                                                                                        } ?>"><?php echo $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages) : ?>
                <a href="index.php?page=<?php echo $page + 1 ?>" class="px-3 py-2 bg-[#0464A4] hover:bg-[#0242A2] text-white rounded-r-md">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <script>
        let likebtn = document.querySelectorAll("#likebtn");
        likebtn.forEach(function(btn) {
            btn.addEventListener("click", function() {
                let currentBtn = this;
                //get 
                let postId = this.dataset.postid;
                let siblingP = currentBtn.nextElementSibling;
                let heartIcon = currentBtn.querySelector("svg");
                //post naar database
                let formData = new FormData();
                formData.append("post_id", postId);
                fetch("ajax/Like.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(json) {
                        siblingP.innerHTML = json.likes;
                        if (json.status == 'Unlike') {
                            heartIcon.setAttribute('fill', '#0464A4');
                        } else {
                            heartIcon.setAttribute('fill', '#C8C8CC');
                        }
                    });
            });
        });
        let promptsID = document.querySelectorAll("#btnFavorites");
        promptsID.forEach(function(btn) {
            btn.addEventListener("click", function() {
                let currentBtn = this;
                let postId = this.dataset.postid;
                //post naar database
                let formData = new FormData();
                formData.append("post_id", postId);
                fetch("ajax/Savefavorite.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(json) {
                        if (json.status == 'success') {
                            currentBtn.innerHTML = json.message;
                        }
                    });
            });
        });
        let reportid = document.querySelectorAll("#reportbtnid");
        reportid.forEach(function(btn) {
            btn.addEventListener("click", function() {
                let currentBtn = this;
                let postId = this.dataset.postid;
                //post naar database
                let formData = new FormData();
                formData.append("post_id", postId);
                fetch("ajax/Report.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(json) {
                        if (json.status == 'success') {
                            currentBtn.innerHTML = json.message;
                        }
                    });
            });
        });
        let buyid = document.querySelectorAll("#buybtnid");
        buyid.forEach(function(btn) {
            btn.addEventListener("click", function() {
                let currentBtn = this;
                let postId = this.dataset.postid;
                let payoutId = this.dataset.postuserid;
                //post naar database
                let formData = new FormData();
                formData.append("post_id", postId);
                formData.append("post_payout_id", payoutId);
                fetch("ajax/Buypost.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(json) {
                        if (json.status == 'success') {
                            currentBtn.innerHTML = json.message;
                        }
                    });
            });
        });
    </script>
</body>

</html>