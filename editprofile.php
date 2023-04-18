<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    if(isset($_SESSION["loggedin"])) {
        $warning = " ";
        $getUser = User::getUser();
        if(!empty($_POST)){
            if(!empty($_POST['bio']) && !empty($_FILES['avatar_url']["name"])){
    
                if (strlen($_POST['bio']) <= 19){
                    $warning = "Bio is too short, has to have at least 20 characters";
                }
                elseif (strlen($_POST['bio']) >= 501){
                    $warning = "Bio is too long, can have a maximum of 500 characters";
                }
                else{
                    $bio = $_POST['bio'];
                    $user = new User();
                    $user->resetBio($bio);
                    //fixing image upload
                    $username = $_SESSION['username'];
                    $orig_file = $_FILES["avatar_url"]["tmp_name"];
                    $ext = pathinfo($_FILES["avatar_url"]["name"], PATHINFO_EXTENSION);
                    $target_dir = "uploads/users/";
                    $destination = "$target_dir$username.$ext";
                    move_uploaded_file($orig_file, $destination);
                    $user->resetAvatar($destination);
                    //saving to database
                    $user->updateUser();
                    header("Location: profile.php");
                }
            }
            elseif(!empty($_POST['bio']) && empty($_FILES['avatar_url']["name"])){
                if (strlen($_POST['bio']) <= 19){
                    $warning = "Bio is too short, has to have at least 20 characters";
                }
                elseif (strlen($_POST['bio']) >= 501){
                    $warning = "Bio is too long, can have a maximum of 500 characters";
                }
                else{
                    $bio = $_POST['bio'];
                    $user = new User();
                    $user->resetBio($bio);
                    $user->updateBio();
                    header("Location: profile.php");
                }
            }
            elseif(empty($_POST['bio']) && !empty($_FILES['avatar_url']["name"])){
                $user = new User();
                //fixing image upload
                $username = $_SESSION['username'];
                $orig_file = $_FILES["avatar_url"]["tmp_name"];
                $ext = pathinfo($_FILES["avatar_url"]["name"], PATHINFO_EXTENSION);
                $target_dir = "uploads/users/";
                $destination = "$target_dir$username.$ext";
                move_uploaded_file($orig_file, $destination);
                $user->resetAvatar($destination);
                //saving to database
                $user->updateAvatar();
                header("Location: profile.php");
            }
            else{
                echo "nothing to save";
            }
        }
    }
    else {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Eve - <?php echo $_SESSION['username']?>'s profile</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <?php if(isset($_SESSION['loggedin'])):?>
        <h1><?php echo $_SESSION['username']?>'s profile</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="">
                <label for="bio">Bio</label>
                <input type="text" name="bio">
                <p class="profile-bio">Bio: <?php echo $getUser["bio"];?></p>
            </div>
            <div class="">
                <label for="avatar_url">Avatar</label>
                <input type="file" id="user-reset-avatar" name="avatar_url" accept="image/*">
                <img src="<?php echo $getUser["avatar_url"] ?>" alt="Avatar">
            </div>
            <p class="editprofilewarning"><?php echo $warning ?></p>
            <input type="submit" value="Save">
        </form>

    <?php else:?>
        <?php header("Location: ./profile.php"); ?>
    <?php endif;?>
</body>
</html>