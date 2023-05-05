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
        <select onchange="document.getElementById('categoryFilter').submit();" name="dropdown" id="dropdown" required class="p-3 rounded-lg bg-[#0464A4] text-white">
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
                <div class="bg-white p-10 rounded-3xl">
                    <ul class=" flex-col">
                        <div class="list-none flex justify-between mb-4">
                        <li class="text-2xl flex items-center text-[#0464A4] font-semibold"><p><?php echo $prompt["title"] ?></p></li>
                        <li class="text-lg flex items-center "><a href="userprofile.php?user=<?php echo $prompt['user_id'] ?>"><?php echo $promptUser['username'] ?></a></li>
                        </div>

                        <?php if(!empty($_SESSION["userid"])): ?>
                            <li><img  class="rounded-3xl w-100 mb-10" src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                            <li class="mb-2"><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li class="mb-2"><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <!-- shouldnt be visible before buying -->
                        <li class="mb-2"><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li class="mb-2"><p><b>Prompt description: </b><?php echo $prompt["prompt-info"] ?></p></li>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <li class="mb-2"><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                        <?php else: ?>
                            <li><img class="blur-lg rounded-3xl w-100 mb-10" src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                            <li class="mb-2 blur-lg"><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li class="mb-2 blur-lg"><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <!-- shouldnt be visible before buying -->
                        <li class="mb-2 blur-lg"><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li class="mb-2 blur-lg"><p><b>Prompt description: </b><?php echo $prompt["prompt-info"] ?></p></li>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <li class="mb-2 blur-lg"><p><b>Category: </b><?php echo $promptCat["category"] ?></p></li>
                            <?php endif; ?>
                        <li class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-3 px-4 mt-8 rounded-lg cursor-pointer flex justify-center"><button>Buy</button></li>
                        <?php if(isset($_SESSION["admin"])):?>
                            <?php if($_SESSION["admin"] == true):?>
                                <li class="bg-[#C8C8CC] hover:bg-[#A0A0A3] text-black font-bold py-3 px-4 rounded-lg cursor-pointer my-4 flex justify-center"><a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a></li>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if(isset($_SESSION["userid"])):?>
                            <?php if($prompt["user_id"] == $_SESSION["userid"]):?>
                                <li class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg cursor-pointer flex justify-center"><a href="deletepost.action.php?pid=<?php echo $prompt["id"] ?>&uid=<?php echo $prompt["user_id"] ?>">Delete</a></li>
                            <?php endif ?>
                        <?php endif ?>
                    </ul>
                    

                </div>
               
    <?php endforeach;} ?>
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
<?php endif; ?>

</body>
</html>