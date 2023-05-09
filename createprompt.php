<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    if(isset($_SESSION["loggedin"])) {

    }
    else {
        header("Location: login.php");
    }
    $categories = [];
    $prices = [];
    $categories = Prompt::getCategories();
    $prices = Prompt::getPrices();
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
            $prompt->setCategoryId($_POST['categories']);
            $prompt->setDescription($_POST['description']);
            $prompt->setPhotoUrl($destination);
            $prompt->setPrompt($_POST['prompt']);
            $prompt->setPromptInfo($_POST['prompt_info']);
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
<body class="bg-[#f4f4fc]">
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1 class="flex justify-center items-center mb-15 text-[#0464A4] text-5xl">New Prompt</h1>
    <div class="flex justify-center items-center">

        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <input type="text" name="title" placeholder="Title" required>
            </div>
            <div>
                <select name="prices" id="prices" required>
                    <option value="" disabled selected>Select a price</option>
                    <?php foreach($prices as $price):?>
                        <option value="<?php echo $price['value']; ?>"><?php echo $price['price']; ?></option>
                    <?php endforeach; ?>
            </div>
            </select>
            <div>
                <input type="text" name="description" placeholder="Description" required>
            </div>
            <div>

                <input type="file" name="photo" required>
            </div>
            <div>
                <select name="categories" id="categories" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="text" name="prompt" placeholder="Prompt" required>
            <input type="text" name="prompt_info" placeholder="Prompt info" required>
            <input type="submit" value="Create Prompt">
        </form>
    </div>
</body>
</html>