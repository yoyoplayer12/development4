<?php
//include bootstrap
include_once(__DIR__ . "/bootstrap.php");
$postid = $_GET['pid'];
//getpostbyid
$prompt = Prompt::getPromptById($postid);
//setting up image getting
$image = new Image();
$url = $image->getUrl();

//get comments
$comments = Comment::getComments($postid);
var_dump($comments);

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
    <title><?php echo $prompt['title'] ?></title>
</head>

<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <?php if (empty($prompt)) {
        echo "<h1 class='noposts'>There are no prompts right now, try again later!</h1>";
    } else { ?>
        <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
        <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
        <?php $promptprice = Prompt::getPromptprice($prompt['price_id']); ?>
        <div class="bg-white p-10 rounded-3xl">
            <ul class="list-none flex flex-col">
                <div class="flex flex-row justify-between mb-5">
                    <li class="text-xl flex">
                        <p><?php echo $prompt["title"] ?></p>
                    </li>
                    <li class="text-lg flex"><a href="userprofile.php?user=<?php echo $prompt['user_id'] ?>"><?php echo $promptUser["username"] ?></a></li>
                </div>
                <?php if (!empty($_SESSION["userid"])) : ?>
                    <li><img class="rounded-3xl" src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                <?php else : ?>
                    <li><img class="blur-lg rounded-3xl w-15 h-15" src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                <?php endif; ?>
                <li class="mt-5">
                    <p><b>Description: </b><?php echo $prompt["description"] ?></p>
                </li>
                <li>
                    <p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p>
                </li>
                <li>
                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                </li>
                <div>
                    <input type="text" placeholder="add comment" id="inputComment">
                    <button id="addComment" class="cursor-pointer" data-promptId="<?php echo $prompt['id'] ?>">Add comment</button>
                </div>
                <ul class="comments">
                    <?php foreach ($comments as $comment) : ?>
                    <li><?php echo $comment['text']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </ul>
        </div>
    <?php } ?>
    <script>
        let addComment = document.querySelectorAll("#addComment");
        addComment.forEach(function(btn) {
            btn.addEventListener("click", function() {
                let currentBtn = this;

                let text = document.querySelector("#inputComment").value;
                let promptId = this.getAttribute("data-promptId");

                let formData = new FormData();
                formData.append("promptId", promptId);
                formData.append("text", text);
                fetch("ajax/AddComment.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(json) {
                        if (json.status == 'success') {
                            //currentBtn.innerHTML = json.message;
                            let makeComment = document.createElement("li");
                            makeComment.innerHTML = json.body;
                            document.querySelector(".comments").appendChild(makeComment);
                            document.querySelector("#inputComment").value = "";
                        }
                    });
            });
        });
    </script>
</body>

</html>