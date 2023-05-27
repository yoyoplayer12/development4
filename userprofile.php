<?php
include_once(__DIR__ . "/bootstrap.php");
//logindetection
if (isset($_SESSION["loggedin"])) {
} else {
    header("Location: login.php");
}
$User = User::getUser($_GET['user']);
$prompts = [];
$prompts = Prompt::getPromptsByUser($User["id"]);
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
    <link rel="icon" type="image/png" href="<?php echo $url."evestore/assets/brand/zfgfkok4d1wqydimxrj7.png"?>">
    <title>Eve - <?php echo $User["username"] ?>'s profile</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <div class="flex justify-center items-center mt-20">
        <div class="w-90 rounded-3xl bg-white px-10 py-10">
            <?php if (isset($_SESSION['loggedin'])) : ?>
                <div class="flex justify-center flex-col items-center">
                    <h1 class="text-2xl font-bold mb-5"><?php echo $User["username"] ?>'s profile</h1>
                    <button class="reportUserbtn" id="reportUserbtnid" data-userId="<?php echo $User["id"]?>"><?php if(count(User::checkReportUser($User['id'])) >=1 ){ echo "Reported";} else { echo "Report";} ?></button>
                    <img src="<?php echo $url . $User["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 flex justify-center mb-5">
                </div>
                <div>
                    <p class="profile-bio">Bio: <?php echo $User["bio"]; ?></p>
                    <h2 class="text-[#0464A4] text-xl my-5 flex justify-center">Prompts</h2>
                    <?php foreach ($prompts as $prompt) : ?>
                        <div class="prompt bg-blue-500 text-white py-5 px-5 rounded-lg">
                            <h3>Title: <?php echo $prompt["title"]; ?></h3>
                            <p>Description: <?php echo $prompt["description"]; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <h1>Log in to see this profile</h1>
                <a href="login.php">Log in</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<script>
    let reportUser = document.querySelectorAll("#reportUserbtnid");
    reportUser.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let currentBtn = this;
            let userId = this.dataset.userid;
            //post naar database
            let formData = new FormData();
            formData.append("reported_id", userId);
            
            fetch("ajax/reportUser.php", {
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
</html>
