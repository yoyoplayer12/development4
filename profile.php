<?php
include_once(__DIR__ . "/bootstrap.php");
//logindetection
if (isset($_SESSION["loggedin"])) {
    $getUser = User::getSessionUser();
} else {
    header("Location: login.php");
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
    <title>Eve - <?php echo $_SESSION['username'] ?>'s profile</title>
</head>

<body>
<?php include_once(__DIR__ . "/nav.php"); ?>
<div class="flex justify-center items-center h-screen">
  <?php if (isset($_SESSION['loggedin'])) : ?>
    <div class=" w-90 rounded-lg bg-white px-10 py-10 ">
      <h1 class="text-2xl font-bold text-center"><?php echo $_SESSION['username'] ?>'s profile</h1>
      <div class="flex flex-col items-center my-4">
        <img src="<?php echo $getUser["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 object-cover m-10">
        <p class="text-gray-700"><?php echo $getUser["bio"]; ?></p>
      </div>
      <div>
        <a href="editprofile.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Edit profile</a>
        <a href="changePassword.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Change password</a>
        <a href="logout.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Log out</a>
        <a href="deleteprofile.action.php" class="mx-2 px-4 py-2 rounded-md bg-red-500 hover:bg-red-600 text-white">Delete profile</a>
      </div>
    </div>
  <?php else : ?>
    <h1 class="text-2xl font-bold">Log in to see your profile</h1>
    <a href="logout.php" class="mx-2 px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Log in</a>
  <?php endif; ?>
</div>



</body>


</html>