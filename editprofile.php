<?php
include_once(__DIR__ . "/bootstrap.php");
//logindetection
if (isset($_SESSION["loggedin"])) {
    $warning = "";
    $getUser = User::getSessionUser();
    if (!empty($_POST)) {
        if (!empty($_POST['bio']) && !empty($_FILES['avatar_url']["name"])) {
            if (strlen($_POST['bio']) <= 19) {
                $warning = "Bio is too short, has to have at least 20 characters.";
            } elseif (strlen($_POST['bio']) >= 501) {
                $warning = "Bio is too long, can have a maximum of 500 characters.";
            } else {
                $bio = $_POST['bio'];
                $user = new User();
                $user->resetBio($bio);
                //set image
                $upload = new Image();
                $upload->setup();
                $upload->upload("public", "users", "avatar_url");
                $randomstring = $upload->getString();
                $ext = pathinfo($_FILES['avatar_url']['name'], PATHINFO_EXTENSION);
                $destination = "evestore/public/users/" . $randomstring . "." . $ext;
                $user->resetAvatar($destination);
                //saving to database
                $user->updateUser();
                header("Location: profile.php");
            }
        } elseif (!empty($_POST['bio']) && empty($_FILES['avatar_url']["name"])) {
            if (strlen($_POST['bio']) <= 19) {
                $warning = "Bio is too short, has to have at least 20 characters";
            } elseif (strlen($_POST['bio']) >= 501) {
                $warning = "Bio is too long, can have a maximum of 500 characters";
            } else {
                $bio = $_POST['bio'];
                $user = new User();
                $user->resetBio($bio);
                $user->updateBio();
                header("Location: profile.php");
            }
        } elseif (empty($_POST['bio']) && !empty($_FILES['avatar_url']["name"])) {
            $user = new User();
            $bio = $_POST['bio'];
            $user = new User();
            $user->resetBio($bio);
            //set image
            $upload = new Image();
            $upload->setup();
            $upload->upload("public", "users", "avatar_url");
            $randomstring = $upload->getString();
            $ext = pathinfo($_FILES['avatar_url']['name'], PATHINFO_EXTENSION);
            $destination = "evestore/public/users/" . $randomstring . "." . $ext;
            $user->resetAvatar($destination);
            //saving to database
            $user->updateAvatar();
            $user->updateUser();
            header("Location: profile.php");
        } elseif (!empty($_POST['bio']) && empty($_FILES['avatar_url']["name"])) {
            if (strlen($_POST['bio']) <= 19) {
                $warning = "Bio is too short, has to have at least 20 characters";
            } elseif (strlen($_POST['bio']) >= 501) {
                $warning = "Bio is too long, can have a maximum of 500 characters";
            } else {
                $bio = $_POST['bio'];
                $user = new User();
                $user->resetBio($bio);
                $user->updateBio();
                header("Location: profile.php");
            }
        } elseif (empty($_POST['bio']) && !empty($_FILES['avatar_url']["name"])) {
            $user = new User();
            //set image
            $upload = new Image();
            $upload->setup();
            $upload->upload("public", "users", "avatar_url");
            $randomstring = $upload->getString();
            $ext = pathinfo($_FILES['avatar_url']['name'], PATHINFO_EXTENSION);
            $destination = "evestore/public/users/" . $randomstring . "." . $ext;
            $user->resetAvatar($destination);
            //saving to database
            $user->updateAvatar();
            header("Location: profile.php");
        } else {
            echo "nothing to save";
        }
    }
} else {
    header("Location: login.php");
}
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
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <title>Eve - <?php echo htmlspecialchars($_SESSION['username']) ?>'s profile</title>
</head>

<body class="bg-blue-200 ">
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <div class="flex justify-center items-center mt-10">
        <div>
            <?php if (isset($_SESSION['loggedin'])) : ?>
                <div class=" w-90 rounded-3xl bg-white px-10 py-10 ">
                    <h1 class="text-2xl font-bold text-center"><?php echo htmlspecialchars($_SESSION['username']) ?>'s profile</h1>
                    <form action="" method="post" enctype="multipart/form-data" class="flex justify-center flex-col">
                        <div class="flex justify-center flex-col items-center mb-5">
                            <label for="avatar_url" class="text-xl font-bold text-center">Avatar</label>
                            <img src="<?php echo $url . $getUser["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 object-cover my-5">
                            <input type="file" id="user-reset-avatar" name="avatar_url" accept="image/*">
                        </div>
                        <div class="flex justify-center flex-col items-center gap-2 mb-5">
                            <label for="bio">Bio</label>
                            <input type="text" name="bio" class="bg-blue-500 px-4 py-2 rounded-xl text-white" placeholder="typ your bio here">
                            <p class="profile-bio">Preview bio: <?php echo htmlspecialchars($getUser["bio"]); ?></p>
                        </div>
                        <p class="editprofilewarning"><?php echo $warning ?></p>
                        <input type="submit" value="Save" class="mx-2 px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white cursor-pointer">
                    </form>
                </div>
            <?php else : ?>
                <?php header("Location: ./profile.php"); ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>