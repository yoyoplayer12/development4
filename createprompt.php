<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    if(isset($_SESSION["loggedin"])) {

    }
    else {
        header("Location: login.php");
    }
    if(!empty($_POST)){
        try{
            $prompt = new Prompt();
            //fixing image
            $randomstring = $prompt->getRandomStringRamdomInt();
            $userId = $_SESSION['userid'];
            //fixing image
            $orig_file = $_FILES["photo"]["tmp_name"];
            $ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
            $target_dir = "uploads/posts/";
            $destination = "$target_dir$randomstring$userId.$ext";
            move_uploaded_file($orig_file, $destination);

            $prompt->setTitle($_POST['title']);
            $prompt->setPrice($_POST['prices']);
            $prompt->setDescription($_POST['description']);
            $prompt->setPhotoUrl($destination);
            $prompt->setPrompt($_POST['prompt']);
            $prompt->setPromptInfo($_POST['prompt-info']);
            $prompt->setUserId($_SESSION["userid"]);
            //final prompt setting
            $prompt->save();
            header("Location: index.php");
            //message meegeven nog doen
          }
          catch(Throwable $e){
            echo $e->getMessage();
          }
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
    <title>Eve - Home</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1>New Prompt</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title" required>
        <label for="prices">Price</label>
        <select name="prices" id="prices" required>
            <option value="0">€0</option>
            <option value="1.99">€1,99</option>
            <option value="3.99">€3,99</option>
            <option value="5.99">€5,99</option>
            <option value="7.99">€7,99</option>
            <option value="9.99">€9,99</option>
        </select>
        <label for="description" required>Description</label>
        <input type="text" name="description">
        <label for="photo" required>Photo</label>
        <input type="file" name="photo">
        <label for="prompt" required>Prompt</label>
        <input type="text" name="prompt">
        <label for="prompt-info" required>Prompt information</label>
        <input type="text" name="prompt-info">
        <input type="submit" value="Create Prompt">
    </form>
</body>
</html>