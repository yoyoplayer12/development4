<?php
include_once(__DIR__ . "/bootstrap.php");
//logindetection
if (isset($_SESSION["loggedin"])) {
} else {
    header("Location: login.php");
}
$user = new User();
$categories = [];
$prices = [];
$categories = Prompt::getCategories();
$prices = Prompt::getPrices();
$userDetails = $user->getUser($_SESSION["userid"]);

if (!empty($_POST)) {
    try {
        $prompt = new Prompt();
        //set image
        $upload = new Image();
        $upload->setup();
        $upload->upload("public", "prompts", "photo");
        $randomstring = $upload->getString();
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $destination = "evestore/public/prompts/" . $randomstring . "." . $ext;
        //setting everything
        $prompt->setTitle($_POST['title']);
        $prompt->setPriceId($_POST['prices']);
        $prompt->setCategoryId($_POST['categories']);
        $prompt->setDescription($_POST['description']);
        $prompt->setPhotoUrl("$destination");
        $prompt->setPrompt($_POST['prompt']);
        $prompt->setPromptInfo($_POST['prompt_info']);
        $prompt->setUserId($_SESSION["userid"]);
        //final prompt setting
        if ($userDetails["verified"] == 0) {
            $prompt->setVerified(0);
        } else {
            $prompt->setVerified(1);
        }
        $prompt->save($userDetails["verified"]);
        header("Location: index.php");
        //message meegeven nog doen
    } catch (Throwable $e) {
        echo $e->getMessage();
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" type="image/png" href="<?php echo $url . "evestore/assets/brand/zfgfkok4d1wqydimxrj7.png" ?>">
    <title>Eve - Home</title>
</head>

<body class="bg-blue-200">
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1 class="text-[#0464A4] text-5xl my-10 flex justify-center">New Prompt</h1>
    <div class="flex justify-center">
        <div class="flex justify-center items-center bg-blue-500 px-10 py-10 rounded-2xl text-white mb-10">
            <form action="" method="post" enctype="multipart/form-data" class="">
                <p class="text-blue-900 text-xl mb-4"><b>Give us some information about your prompt</b></p>
                <div class="mb-5">
                    <p>Choose a title for your prompt:</p>
                    <input type="text" name="title" placeholder="Title" required class="mx-2 px-4 py-2 rounded-md bg-white text-blue-500">
                </div>
                <div class="mb-5">
                    <p>Choose a price for your prompt:</p>
                    <select name="prices" id="prices" required class="text-black px-4 py-4 bg-blue-800 px-5 py-3 rounded-lg text-white cursor-pointer">
                        <option value="" disabled selected>Select a price</option>
                        <?php foreach ($prices as $price) : ?>
                            <option value="<?php echo $price['id']; ?>" class="text-black"><?php echo $price['price']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-5">
                    <p>Make a description for your prompt:</p>
                    <input type="text" name="description" placeholder="Description" required class="mx-2 px-4 py-2 rounded-md bg-white text-blue-500">
                </div>
                <div class="mb-5">
                    <p>Upload an image made with your prompt:</p>
                    <input type="file" name="photo" id="photo" required class="mx-2 px-4 py-2 rounded-md bg-blue-800 text-white">
                </div>
                <div class="mb-5">
                    <p>Select a category for your prompt:</p>
                    <select name="categories" id="categories" required class="text-black px-4 py-4 bg-blue-800 px-5 py-3 rounded-lg text-white cursor-pointer">
                        <option value="" disabled selected>Select a category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['id']; ?>" class="text-black"><?php echo $category['category']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-5">
                    <p class="text-blue-900 text-xl mt-8 mb-4"><b>How do you make your prompt?</b></p>
                    <p>Enter here what you entered to create this prompt:</p>
                    <input type="text" name="prompt" placeholder="Prompt" required class="mx-2 px-4 py-2 rounded-md bg-white text-blue-500">
                </div>
                <div class="mb-5">
                    <p>Enter any other information about your prompt e.g. made with?:</p>
                    <input type="text" name="prompt_info" placeholder="Prompt info" required class="mx-2 px-4 py-2 rounded-md bg-white text-blue-500">
                </div>
                <div>
                    <input type="submit" value="Create Prompt" class="mx-2 px-4 py-2 rounded-md bg-blue-800 hover:bg-blue-700 text-white cursor-pointer">
                </div>
            </form>
        </div>
    </div>
</body>

</html>