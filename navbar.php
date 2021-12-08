<?php require_once("functions.php"); ?>
<!DOCTYPE html>
<html lang = "en">
<head>
    <link rel="stylesheet" type= "text/css" href = "css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body{
            height: 100%;
        }
        body {
            margin: 0;
            padding: 0;
        }
        ul.navbar{
            margin: 0;
            list-style-type: none;
            overflow: hidden;
            background-color: #333;
        }
        ul.navbar li{
            float: left;
        }
        ul.navbar li a{
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        ul.navbar li a.active{
            background-color: #5352ed;
        }
        ul.navbar li.right{
            float: right;
            margin-right: 10px;
        }
        @media screen and (max-width: 600px){
            ul.navbar li.right,
            ul.navbar li{
                float: none;
            }
        }

    </style>
</head>
<body>
<ul class="navbar">
    <li><a class = "active" href = "index.php">Home</a></li>
    <li><a href = "bot.php?certificated=yes">Certificated Bots</a></li>
    <?php
    if(isset($_SESSION["user"])) {
        $user = apiRequest("https://discord.com/api/users/@me");
        echo("<li class = 'right'><a href = 'profile.php'>" . $user->username . "#" . $user->discriminator . "</a></li>");
        echo("<li class = 'right'><a href = 'bot-add.php'>Add A Bot</a></li>");
        echo("<li class = 'right'><a href = 'process.php?action=logout'>Log out</a></li>");
        echo("<li><a href = 'edit.php'>Edit A Bot</a></li>");
        echo("<li><a href = 'delete.php'>Delete A Bot</a></li>");
    } else {
        echo("<li class = 'right'><a href = 'process.php?action=login'>Log in</a></li>");
    }
    ?>

</ul>

</body>
</html>