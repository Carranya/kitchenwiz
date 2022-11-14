<?php
    require_once "functions/bootingPage.php";
    include "pages/devPages/devButtons.php";
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen-Wiz</title>
    <link rel="stylesheet" href="css/output.css">
    <script src="./node_modules/tw-elements/dist/js/index.min.js"></script>
</head>
<body class='font-sans bg-violet-600'>
    <div>
        <?php
            include "pages/menu.php";
            // include "pages/test.php";
        ?>
    </div>
</body>
</html>