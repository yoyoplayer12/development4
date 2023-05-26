<?php
include_once("./bootstrap.php");

if (isset($_SESSION["admin"]) && $_SESSION["admin"] == true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchedUsername = isset($_POST["username"]) ? $_POST["username"] : ""; // Searched username

        // Get all usernames from the database
        $usernames = Moderator::getUsernames($searchedUsername);
        $users = Moderator::getUsers($searchedUsername);
        // Filter the usernames based on the search value
        foreach ($users as $user) {
            if (stripos($user["username"], $searchedUsername) !== false) {
                $matchingUserIds[] = $user["id"];
            }
        }

        // Check if the "addMod" button is clicked
        if (isset($_POST["addMod"]) && isset($_POST["selectedUserId"])) {
            $selectedUserId = $_POST["selectedUserId"];
            Moderator::addModerator($selectedUserId);
        }

        // Check if the "deleteMod" button is clicked
        if (isset($_POST["deleteMod"]) && isset($_POST["selectedUserId"])) {
            $selectedUserId = $_POST["selectedUserId"];
            Moderator::deleteModerator($selectedUserId);
        }
        //ban user
        if (isset($_POST["banuser"]) && isset($_POST["selectedUserId"])) {
            $selectedUserId = $_POST["selectedUserId"];
            Moderator::ban($selectedUserId);
        }
        //unban user
        if (isset($_POST["unbanuser"]) && isset($_POST["selectedUserId"])) {
            $selectedUserId = $_POST["selectedUserId"];
            Moderator::unban($selectedUserId);
        }
        if (isset($_POST["unban"]) && isset($_POST["selectedUserId"])) {
            $selectedUserId = $_POST["selectedUserId"];
            Moderator::unban($selectedUserId);
        }
    }

    // Fetch and display the list of moderators
    $moderators = Moderator::getModerators();
} else {
    header("Location: index.php");
    exit;
}


$unverifiedprompts = [];
$rejectedprompts = [];
$reportedprompts = [];
$bannedusers = [];
$reporteduserids = [];
$rejectedpromptscount = 0;
$unverifiedpromptscount = 0;
$reportedpromptscount = 0;
$unverifiedprompts = Prompt::getUnverifiedPrompts();
$rejectedprompts = Prompt::getRejectedPrompts();
$reportedprompts = Prompt::getReportedPrompts();
$bannedusers = Moderator::getBannedUsers();
$reporteduserids = Moderator::getReportedUserIds();
$rejectedpromptscount = count($rejectedprompts);
$unverifiedpromptscount = count($unverifiedprompts);
$reportedpromptscount = count($reportedprompts);

// $printReportedUsers = User::getReportedUsers();









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
    <title>EVE - Moderator Panel</title>
</head>

<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1 class="text-[#0464A4] text-5xl my-10 flex justify-center">Moderator Panel</h1>
    <div class="flex justify-center flex-row items-center gap-5">
        <a href="#users" class="px-5 py-2 text-[#0464A4] underline bg-white rounded">Users</a>
        <a href="#moderators" class="px-5 py-2 text-[#0464A4] underline bg-white rounded">Moderators</a>
        <a href="#prompts" class="px-5 py-2 text-[#0464A4] underline bg-white rounded">Prompts</a>
        <a href="#bannedusers" class="px-5 py-2 text-[#0464A4] underline bg-white rounded">Banned users</a>
        <a href="#reportedusers" class="px-5 py-2 text-[#0464A4] underline bg-white rounded">Reported users</a>
        
    </div>
    <div class="flex justify-center flex-col items-center">
        <div>
            <div id="users">
                <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Users</h2>
                <form method="POST" class="mr-2">
                    <input type="text" name="username" class="p-3 border-2 rounded-lg border-[#0464A4] w-96" placeholder="Search">
                    <button type="submit" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-3 px-8 rounded-lg mx-4 cursor-pointer">Search</button>
                </form>

                <!-- Display the list of matching usernames -->
                <?php if (!empty($matchingUserIds)) : ?>
                    <div class="mt-2">
                        <h2 class="text-[#0464A4] text-xl my-5 flex justify-center">Users:</h2>
                        <ul>
                            <?php foreach ($matchingUserIds as $userid) : ?>
                                <?php $user = Moderator::getUserById($userid); ?>
                                <form method="POST">
                                    <li class="mb-5 bg-white px-4 py-4 rounded-lg flex items-center justify-between">
                                        <?php echo htmlspecialchars($user['username']); ?>
                                        <input type="hidden" name="selectedUserId" value="<?php echo htmlspecialchars($user['id']); ?>">
                                        <button type="submit" name="addMod" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-1 px-4 rounded-lg mx-4 cursor-pointer">Add as Moderator</button>
                                        <button type="submit" name="<?php if ($user['banned'] == 0) {
                                            echo "banuser";
                                        } else {
                                            echo "unbanuser";
                                        } ?>" class="bg-red-500 hover:bg-[#0242A2] text-white font-bold py-1 px-4 rounded-lg mx-4 cursor-pointer"><?php if ($user['banned'] == 0) {
                                            echo "Ban";
                                        } else {
                                            echo "Unban";
                                        } ?></button>
                                    </li>
                                </form>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else : ?>
                    <div class="mt-5">
                        <h2>No users were found.</h2>
                    </div>
                <?php endif; ?>


            </div>
            <div>
                <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center" id="moderators">Moderators</h2>
                <ul>
                    <?php foreach ($moderators as $moderator) : ?>
                        <li class="mb-5 mt-5 bg-white px-4 py-4 rounded-lg flex items-center justify-between">
                            <?php echo htmlspecialchars($moderator["username"]); ?>
                            <form method="POST">
                                <input type="hidden" name="selectedUserId" value="<?php echo htmlspecialchars($moderator["id"]); ?>">
                                <button type="submit" name="deleteMod" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-4 rounded-lg mx-4 cursor-pointer ">Remove Moderator</button>
                            </form>
                        </li>

                        </form>

                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="prompts">
                <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Prompts</h2>
                <h1><?php echo $unverifiedpromptscount ?> To verify</h1>
                <?php
                if (empty($unverifiedprompts)) {
                    echo "<h1 class='noposts'>Congrats, you verified them all!</h1>";
                } else {
                    foreach ($unverifiedprompts as $prompt) : ?>
                        <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                        <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                        <?php $promptprice = Prompt::getPromptprice($prompt['price_id']); ?>
                        <div class="prompt">
                            <ul>
                                <li>
                                    <p><b>Title: </b><?php echo $prompt["title"] ?></p>
                                </li>
                                <li>
                                    <p><b>User: </b><?php echo $promptUser['username'] ?></p>
                                </li>
                                <li>
                                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                                </li>
                                <li>
                                    <p><b>Price: </b><?php echo $promptprice["price"] ?></p>
                                </li>
                                <li><img src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                                <li>
                                    <p><b>Description: </b><?php echo $prompt["description"] ?></p>
                                </li>
                                <li>
                                    <p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p>
                                </li>
                                <!-- Hier komt de verify button ==> if verify = 0 ==> andere backgroundcolor en text -->
                                <a href="verify.action.php?id=<?php echo $prompt['id'] ?>&username=<?php echo $promptUser['username'] ?>&user_id=<?php echo $promptUser['id'] ?>">Approve</a>
                                <a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a>
                            </ul>
                        </div>
                <?php endforeach;
                } ?>
                <h1><?php echo $rejectedpromptscount ?> Rejected</h1>
                <?php
                if (empty($rejectedprompts)) {
                    echo "<h1 class='noposts'>No rejected posts yet...</h1>";
                } else {
                    foreach ($rejectedprompts as $prompt) : ?>
                        <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                        <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                        <div class="prompt">
                            <ul>
                                <li>
                                    <p><b>Title: </b><?php echo $prompt["title"] ?></p>
                                </li>
                                <li>
                                    <p><b>User: </b><?php echo $promptUser['username'] ?></p>
                                </li>
                                <li>
                                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                                </li>
                                <li><img src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                                <li>
                                    <p><b>Description: </b><?php echo $prompt["description"] ?></p>
                                </li>
                                <li>
                                    <p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p>
                                </li>
                            </ul>
                        </div>
                <?php endforeach;
                } ?>
                <h1><?php echo $reportedpromptscount ?> Reported</h1>
                <?php
                if (empty($reportedprompts)) {
                    echo "<h1 class='noposts'>No reported posts yet...</h1>";
                } else {
                    foreach ($reportedprompts as $reportedprompt) : ?>
                        <?php $prompt = Prompt::getPromptById($reportedprompt['prompt_id']) ?>
                        <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                        <?php $promptCat = Prompt::getPromptCat($prompt['cat_id']); ?>
                        <div class="prompt">
                            <ul>
                                <li>
                                    <p><b>Title: </b><?php echo $prompt["title"] ?></p>
                                </li>
                                <li>
                                    <p><b>Report count: </b><?php echo $reportedprompt["count"] ?></p>
                                </li>
                                <li>
                                    <p><b>User: </b><?php echo $promptUser['username'] ?></p>
                                </li>
                                <li>
                                    <p><b>Category: </b><?php echo $promptCat["category"] ?></p>
                                </li>
                                <li><img src="<?php echo $url . $prompt["photo_url"] ?>" alt="Prompt photo"></li>
                                <li>
                                    <p><b>Description: </b><?php echo $prompt["description"] ?></p>
                                </li>
                                <li>
                                    <p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p>
                                </li>
                                <li>
                                    <p><b>Prompt description: </b><?php echo $prompt["prompt_info"] ?></p>
                                </li>
                            </ul>
                        </div>
                <?php endforeach;
                } ?>
            </div>
            <div id="users" class="mb-10">
                <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Banned users</h2>
                <?php foreach ($bannedusers as $user) : ?>
                    <form method="POST">
                        <li class="mb-5 bg-white px-4 py-4 rounded-lg flex items-center justify-between">
                            <?php echo htmlspecialchars($user['username']); ?>
                            <input type="hidden" name="selectedUserId" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <button type="submit" name="unban" class="bg-red-500 hover:bg-[#0242A2] text-white font-bold py-1 px-4 rounded-lg mx-4 cursor-pointer"><?php if ($user['banned'] == 0) {
                                echo "Ban";
                            } else {
                                echo "Unban";
                            } ?></button>
                        </li>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>