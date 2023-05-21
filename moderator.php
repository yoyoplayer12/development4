<?php
include_once("./bootstrap.php");
include_once("./classes/Db.php");
require_once 'Moderator.php';

if (isset($_SESSION["admin"]) && $_SESSION["admin"] == true) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchedUsername = isset($_POST["username"]) ? $_POST["username"] : ""; // Searched username

        // Get all usernames from the database
        $usernames = Moderator::getUsernames($searchedUsername);

        // Filter the usernames based on the search value
        foreach ($usernames as $user) {
            if (stripos($user["username"], $searchedUsername) !== false) {
                $matchingUsernames[] = $user["username"];
            }
        }

        // Check if the "addMod" button is clicked
        if (isset($_POST["addMod"]) && isset($_POST["selectedUsername"])) {
            $selectedUsername = $_POST["selectedUsername"];
            Moderator::addModerator($selectedUsername);
        }

        // Check if the "deleteMod" button is clicked
        if (isset($_POST["deleteMod"]) && isset($_POST["selectedUsername"])) {
            $selectedUsername = $_POST["selectedUsername"];
            Moderator::deleteModerator($selectedUsername);
        }
    }

    // Fetch and display the list of moderators
    $moderators = Moderator::getModerators();
} else {
    header("Location: index.php");
    exit;
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
    <title>EVE - Moderator Panel</title>
</head>

<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1 class="text-[#0464A4] text-5xl my-10 flex justify-center">Moderator Panel</h1>
    <div class="flex justify-center flex-col items-center">
        <form method="POST" class="mr-2">
            <input type="text" name="username" class="p-3 border-2 rounded-lg border-[#0464A4] w-96" placeholder="Search">
            <button type="submit" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-3 px-8 rounded-lg mx-4 cursor-pointer">Search</button>
        </form>

        <!-- Display the list of matching usernames -->
        <?php if (!empty($matchingUsernames)) : ?>
            <div class="mt-2">
                <h2 class="text-[#0464A4] text-xl my-5 flex justify-center">Possible usernames:</h2>
                <ul>
                    <?php foreach ($matchingUsernames as $username) : ?>
                        <form method="POST">
                            <li class="mb-5 bg-white px-4 py-4 rounded-lg flex items-center justify-between">
                                <?php echo $username; ?>
                                <input type="hidden" name="selectedUsername" value="<?php echo $username; ?>">
                                <button type="submit" name="addMod" class="bg-[#0464A4] hover:bg-[#0242A2] text-white font-bold py-1 px-4 rounded-lg mx-4 cursor-pointer">Add as Moderator</button>
                            </li>
                        </form>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else : ?>
            <div class="mt-5">
                <h2>No usernames found. You need to search for a username to add a moderator.</h2>
            </div>
        <?php endif; ?>

        <div>
            <h2 class="text-[#0464A4] text-3xl my-10 flex justify-center">Moderators</h2>
            <ul>
                <?php foreach ($moderators as $moderator) : ?>
                    <li class="mb-5 bg-white px-4 py-4 rounded-lg flex items-center justify-between">
    <?php echo $moderator["username"]; ?>
    <form method="POST">
        <input type="hidden" name="selectedUsername" value="<?php echo $moderator["username"]; ?>">
        <button type="submit" name="deleteMod" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-4 rounded-lg mx-4 cursor-pointer ">Remove Moderator</button>
    </form>
</li>

                    </form>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>