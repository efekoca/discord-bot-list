<?php ob_start(); 
require_once("functions.php");
require_once("dbConnect.php");

if(empty($_SESSION["user"])){
    echo('<meta http-equiv = "refresh" content = "5; URL = process.php?action=login">');
    echo "<div align = 'center'>";
    echo "Please log in!";
    echo "</div>";
    $connect = null;
    die();
} else {

define("discordAPI", "https://discord.com/api/users/@me");
$user = apiRequest(discordAPI);

if(!$_POST){ ?>

    <!DOCTYPE html>
    <html lang = "en">
    <head>
        <title>Discord Bot List - Add a bot</title>
       <link rel = "stylesheet" type="text/css" href = "css/form.css">
              <link rel = "stylesheet" type="text/css" href = "css/style.css">
    </head>
    <body>
            <?php require("navbar.php"); ?>
    <center>
    <div class = "form">
        <h3> Add a bot! </h3>
    <form action="bot-add.php" method="post">
        <input type = "text" placeholder = "Please enter ID." name = "id" class = "inp" required><br /><br />
        <select required class = "inp" name = "library" style = "color: #60a3bc">
            <option disabled selected value = "other">Select a library</option>
            <option value = "discord.php">discord.php</option>
            <option value = "discord.py">discord.py</option>
            <option value = "discord.js">discord.js</option>
            <option value = "other">Other</option>
        </select><br /><br />
        <input type = "text" placeholder = "Please enter prefix." name = "prefix" class = "inp" required><br /><br />
        <input type = "text" placeholder = "Please enter short desc." name = "shortdesc" class = "inp" required><br /><br />
        <input type = "text" placeholder = "Please enter detailed desc." name = "detaileddesc" class = "inp" required><br /><br />
        <select required style = "height: 100px; color: #60a3bc;" class = "inp" name = "tags[]" multiple>
            <option disabled selected value = "other">Select tags(s)</option>
            <option value = "moderation">Moderation</option>
            <option value = "music">Music</option>
            <option value = "fun">Fun</option>
            <option value = "other">Other</option>
        </select><br /><br />
        <input type = "url" placeholder = "Please enter website. (Optional)" name = "website" class = "inp"><br /><br />
        <input type = "text" placeholder = "Please enter support server. (Optional)" name = "supserver" class = "inp"><br /><br />
        <input type = "url" placeholder = "Please enter invite URL." name = "invite" class = "inp" required><br /><br />

        <button type="submit"> Submit
          <span></span>
        </button>

    </form>
    </div>
    </center>

<?php
}elseif((!empty($_POST["prefix"])) and (!empty($_POST["shortdesc"])) and (!empty($_POST["library"])) and (!empty($_POST["id"])) and (!empty($_POST["invite"])) and (!empty($_POST["tags"])) and (!empty($_POST["detaileddesc"]))){
    $getClass = new getData();
    if(strlen(filter($_POST["prefix"])) > 50){
        echo("<center>Please enter a correct prefix!</center>");
        $connect = null;
        die();
    }elseif(strlen(filter($_POST["shortdesc"])) > 200){
        echo("<center>Please enter a short description of less than two hundred characters!</center>");
         $connect = null;
        die();
    }elseif(strlen(filter($_POST["detaileddesc"])) > 2000){
        echo("<center>Please enter a detailed description of less than two thousand characters!</center>");
        $connect = null;
        die();
    }elseif((!empty($_POST["website"])) and (strlen(filter($_POST["website"])) > 300)){
        echo("<center>Please enter a correct website!</center>");
        $connect = null;
        die();
    }elseif((!empty($_POST["supserver"])) and (strlen(filter($_POST["supserver"])) > 100)){
        echo("<center>Please enter a correct support server link!</center>");
        $connect = null;
        die();
    }elseif((!empty($_POST["invite"])) and (strlen(filter($_POST["invite"])) > 300)){
        echo("<center>Please enter a correct invite link!</center>");
        $connect = null;
        die();
    }elseif(!empty($_POST["website"])){
        $filteredSite = filter($_POST["website"]);
        $patternOne = "/(http(s)?\:\/\/.)?(www\.)?[a-zA-Z0-9\.\:\+\-\_\#\=\%\~\@]{2,256}\.[a-z]{2,6}\b([a-zA-Z0-9\.\:\+\-\_\#\=\%\~\@]*)+/";
        $controlOne = preg_match($patternOne, $filteredSite);
        $patternTwo = "/(http(s)?\:\/\/.)/";
        $controlTwo = preg_match($patternTwo, $filteredSite);
        $patternThree = "/\.[a-z]{2,6}\b([a-zA-Z0-9\.\:\+\-\_\#\=\%\~\@]*)+/";
        $controlThree = preg_match($patternThree, $filteredSite);
        if(($controlOne == 0) and ($controlThree == 0)){
            echo("<center>Please enter a correct website link!</center>");
            $connect = null;
            die();
        }elseif(($controlOne == 1) and ($controlTwo == 0)){
            $formattedSite = "http://" . $filteredSite;
        }elseif(($controlOne == 1) and ($controlTwo == 1)){
            $formattedSite = $filteredSite;
        }
    }
    $query = $connect->prepare("SELECT * FROM bots WHERE id = ?");
    $query->execute(array(filter($_POST["id"])));
    $queryLength = $query->rowCount();
    $control = $connect->prepare("SELECT * FROM bots WHERE owner = ?");
    $control->execute(array($user->id));
    $controlLength = $control->rowCount();
    if($queryLength > 0){
        echo("<center>The bot, you are trying to add is already added in our system!</center>");
        $connect = null;
        die();
    }elseif($getClass->botControl(filter($_POST["id"])) == "no"){
        echo("<center>Please enter a bot ID!</center>");
        $connect = null;
        die();
    }elseif($controlLength >= 5){
        echo("<center>Wops! You can add up to five bots!</center>");
        $connect = null;
        die();
    }else{
       $tags = "";
       foreach($_POST["tags"] as $one){
           if($tags == ""){
               $tags = $tags . filter($one);
           }else{
               $tags = $tags . "," . filter($one);
           }
       }
       $count = $connect->prepare("SELECT * FROM bots");
       $count->execute();
        $database = $connect->prepare("INSERT INTO bots (id, library, prefix, shortdesc, detaileddesc, tags, invite, name, owner, vote) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $database->execute(array(
            filter($_POST["id"]),
            filter($_POST["library"]),
            filter($_POST["prefix"]),
            filter($_POST["shortdesc"]),
            filter($_POST["detaileddesc"]),
            $tags,
            filter($_POST["invite"]),
            filter($getClass->getBotName(filter($_POST["id"]))),
            $user->id,
            intval(0),
        ));
            if((empty(filter($_POST["website"]))) and (!empty(filter($_POST["supserver"])))){
                $secData = $connect->prepare("UPDATE bots SET supserver = ? WHERE id = ? LIMIT 1");
                $secData->execute(array(
                    filter($_POST["supserver"]),
                    filter($_POST["id"]),
                ));
            }elseif((empty(filter($_POST["supserver"]))) and (!empty(filter($_POST["website"])))){
                $secData = $connect->prepare("UPDATE bots SET website = ? WHERE id = ? LIMIT 1");
                $secData->execute(array(
                    filter($_POST["website"]),
                    filter($_POST["id"]),
                ));
            }elseif((!empty(filter($_POST["supserver"]))) and (!empty(filter($_POST["website"])))){
                $secData = $connect->prepare("UPDATE bots SET website = ?, supserver = ? WHERE id = ? LIMIT 1");
                $secData->execute(array(
                    filter($_POST["website"]),
                    filter($_POST["supserver"]),
                    filter($_POST["id"]),
                ));
            }
    }

    ?>
    <center>
        <div>
            <p>Your bot successfully added into our system! You are being redirect soon.</p>
            <?php $filteredID = filter($_POST["id"]); ?>
            <meta http-equiv = "refresh" content = "5; URL = bot.php?id=<?php echo("{$filteredID}"); ?>">
        </div>
    </center>

<?php }else{
    $connect = null;
    ob_end_flush();
    header("Location: ./");
    die();
} $connect = null; } ob_end_flush(); ?>

</body>
</html>