<?php
include_once(__DIR__ . "/bootstrap.php");

if (!empty($_POST)) {
    if (!empty($_POST['currentPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newPassword2'])) {
        $user = new User();
        $user->comparePassword();
        if ($user->comparePassword() == true && $_POST['newPassword'] == $_POST['newPassword2']) {
            $user->setPassword($_POST['newPassword']);
            $user->updatePassword();
            $success = "Password changed successfully!";
        } else {
            $error2 = "Current password is incorrect or new passwords don't match!";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
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
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <title>Change password</title>
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <div >
        <h2 class="text-[#0464A4] text-5xl my-10 flex justify-center">Change password from <?php echo $_SESSION['username'] ?>'s account</h2>
        <form action="" method="post" class="flex justify-center">
            <ul class="flex flex-col items-center">
                <li><input name="currentPassword" type="password" placeholder="current password" class="border-2 flex w-80 justify-center rounded-md mb-5 mt-5 py-2 p-2"></li>
                <li><input name="newPassword" type="password" placeholder="new password" class="border-2 flex w-80 justify-center rounded-md mb-5 py-2 p-2"></li>
                <li><input name="newPassword2" type="password" placeholder="confirm new password" class="border-2 flex w-80 justify-center rounded-md mb-5 py-2 p-2"></li>
                <?php if (isset($error)) : ?>
                    <div class="text-[#FF0000] mb-5"> <?php echo $error ?></div>
                    <?php endif; ?>
                    <?php if (isset($success)) : ?>
                        <div> <?php echo $success ?></div>
                        <?php endif; ?>
                        <?php if (isset($error2)) : ?>
                            <div class="flex justify-center text-[#FF0000] mb-5"> <?php echo $error2 ?></div>
                            <?php endif; ?>
                            <li><input type="submit" value="change password" name="changePassword" class="px-8 py-3 rounded-md bg-[#0464A4] hover:bg-[#0444A4] font-semibold text-white cursor-pointer mb-5"></li>
            </ul>
        </form>

    </div>
</body>

</html>