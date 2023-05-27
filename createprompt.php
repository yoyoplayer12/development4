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

<body class="bg-[#C8C8CC]">
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1 class="text-[#0464A4] text-5xl my-10 flex justify-center">New Prompt</h1>
    <div class="flex justify-center">
    <div class="bg-white px-10 py-10 rounded-2xl text-blue-800 mb-10">
        <form action="" method="post" enctype="multipart/form-data">
            <p class="text-blue-900 text-xl mb-4"><b>Give us some information about your prompt</b></p>
            <div class="mb-5">
                <p>Choose a title for your prompt:</p>
                <input type="text" name="title" placeholder="Title" required class="w-full px-4 py-2 rounded-md bg-slate-200 text-blue-500">
            </div>
            <div class="mb-5">
                <p>Choose a price for your prompt:</p>
                <select name="prices" id="prices" required class="w-full py-3 bg-blue-200 px-4 rounded-lg text-blue-500 cursor-pointer">
                    <option value="" disabled selected>Select a price</option>
                    <?php foreach ($prices as $price) : ?>
                        <option value="<?php echo $price['id']; ?>" class="text-blue-500"><?php echo $price['price']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5">
                <p>Make a description for your prompt:</p>
                <input type="text" name="description" placeholder="Description" required class="w-full px-4 py-2 rounded-md bg-slate-200 text-blue-500">
            </div>
            <div class="mb-5">
                <p>Upload an image made with your prompt:</p>
                <input type="file" name="photo" id="photo" required class="w-full px-4 py-2 rounded-md bg-blue-200 text-blue-500">
            </div>
            <div class="mb-5">
                <p>Select a category for your prompt:</p>
                <select name="categories" id="categories" required class="w-full px-4 py-3 bg-blue-200 rounded-lg text-blue-500 cursor-pointer">
                    <option value="" disabled selected>Select a category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['id']; ?>" class="text-black"><?php echo $category['category']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-5">
                <p class="text-blue-900 text-xl mt-8 mb-4"><b>How do you make your prompt?</b></p>
                <p>Enter here what you entered to create this prompt:</p>
                <input type="text" name="prompt" placeholder="Prompt" required class="w-full px-4 py-2 rounded-md bg-slate-200 text-blue-500">
            </div>
            <div class="mb-5">
                <p>Enter any other information about your prompt e.g. made with?:</p>
                <input type="text" name="prompt_info" placeholder="Prompt info" required class="w-full px-4 py-2 rounded-md bg-slate-200 text-blue-500">
            </div>
            <div class="mb-5">
                <input type="submit" value="Create Prompt" class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-700 text-white cursor-pointer">
            </div>
        </form>
    </div>
</div>

</body>

</html>