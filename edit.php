<?php ob_start();
require_once("functions.php"); 
require_once("dbConnect.php"); 
    if (empty($_SESSION["user"])) {
        echo('<meta http-equiv = "refresh" content = "5; URL = process.php?action=login">');
        echo "<div align = 'center'>";
        echo "Please log in!";
        echo "</div>";
        ob_end_flush();
        die();
    }
    if (!$_POST){ ?>

<!DOCTYPE html>
<html>
<head>
    <title>Discord Bot List - Edit A Bot!</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap"
        rel="stylesheet"
    />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="en" />
    <meta charset="utf-8" />
    <style>
        .success {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success {
            flex-direction: column;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel = "stylesheet" type="text/css" href = "css/form.css">
    <link rel = "stylesheet" type="text/css" href = "css/style.css">
</head>
<body>
        <?php require("navbar.php");
        define("discordAPI", "https://discord.com/api/users/@me");
        $user = apiRequest(discordAPI);
        $getBots = $connect->prepare("SELECT * FROM bots WHERE owner = ?");
        $getBots->execute(array($user->id));
        if($getBots->rowCount() < 1){
            $connect = null;
            header("Location: ./bot-add.php");
            die();
        }
        $botsOfCurrent = $getBots->fetchAll(PDO::FETCH_ASSOC);
        ?>
    <center>
        <div class = "form">
            <h3> Edit a bot! (Note: Only the datas you specify will be updated!) </h3>
            <form action="edit.php" method="post">
                <select required class="inp" name="id" id="botSelect" style ="color: #60a3bc">
                   <option disabled selected value = "other">Select a bot</option>
                    <?php foreach($botsOfCurrent as $bot){
                        echo("<option value='{$bot["id"]}'>{$bot["name"]}</option>");
                    } ?>
                </select>       <br /><br /><br /><br />
                <select required class = "inp" name = "library" id="library" style = "color: #60a3bc">
                    <option disabled selected value = "other">Select a library</option>
                    <option value = "discord.php">discord.php</option>
                    <option value = "discord.py">discord.py</option>
                    <option value = "discord.js">discord.js</option>
                    <option value = "other">Other</option>
                </select><br /><br />
                <input type = "text" id="prefix" placeholder = "Please enter prefix." name = "prefix" class = "inp" required><br /><br />
                <input type = "text" id="shortdesc" placeholder = "Please enter short desc." name = "shortdesc" class = "inp" required><br /><br />
                <input type = "text" id="detaileddesc" placeholder = "Please enter detailed desc." name = "detaileddesc" class = "inp" required><br /><br />
                <select required style = "height: 100px; color: #60a3bc;" class = "inp" name = "tags[]" multiple>
                    <option disabled selected value = "other">Select tags(s)</option>
                    <option value = "moderation">Moderation</option>
                    <option value = "music">Music</option>
                    <option value = "fun">Fun</option>
                    <option value = "other">Other</option>
                </select><br /><br />
                <input type = "url" id="website" placeholder = "Please enter website. (Optional)" name = "website" class = "inp"><br /><br />
                <input type = "text" id="supserver" placeholder = "Please enter support server. (Optional)" name = "supserver" class = "inp"><br /><br />
                <input type = "url" id="invite" placeholder = "Please enter invite URL." name = "invite" class = "inp" required><br /><br />
                <button type="submit"> Submit
          <span></span>
        </button>

            </form>
        </div>
    </center>
                    <script type="text/javascript">
                        document.getElementById("botSelect").onchange = function(){
                            var index = this.selectedIndex;
                            var inputText = this.children[index].value;
                                let req = $.ajax({
                                    type: "GET",
                                    url: "api.php",
                                    data: {
                                        "id": inputText,
                                    },
                                });
                                req.done(function(response, textStatus, jqXHR){
                                    let formattedRes = JSON.parse(JSON.stringify(response));
                                    let lastResult = JSON.parse(formattedRes);
                                    if(lastResult.error < 1){
                                        console.log(lastResult.data)
                                        document.getElementById("library").value = lastResult.data.library;
                                        document.getElementById("prefix").value = lastResult.data.prefix;
                                        document.getElementById("shortdesc").value = lastResult.data.shortdesc;
                                        document.getElementById("detaileddesc").value = lastResult.data.detaileddesc;
                                        document.getElementById("invite").value = lastResult.data.invite;
                                        if(lastResult.data.website != ""){
                                            document.getElementById("website").value = lastResult.data.website;
                                        }
                                        if(lastResult.data.supserver != ""){
                                           document.getElementById("supserver").value = lastResult.data.supserver;
                                        }
                                    }
                                });
                                req.fail(function(jqXHR, textStatus, errorThrown){
                                    console.log("An unexpected error occurred.")
                                });
                        }
                </script>
</body>
</html>

<?php }else{
        if((empty($_POST["prefix"])) or (empty($_POST["shortdesc"])) or (empty($_POST["id"])) or (empty($_POST["invite"])) or (empty($_POST["detaileddesc"]))){
            $connect = null;
            ob_end_flush();
            header("Location: ./");
            die();
        }
        define("discordAPI", "https://discord.com/api/users/@me");
        $user = apiRequest(discordAPI);
        $botID = filter($_POST["id"]);
        $botControl = $connect->prepare("SELECT * FROM bots WHERE id = ?");
        $botControl->execute(array(filter($_POST["id"])));
        $botControlLength = $botControl->rowCount();
        $botControlData = $botControl->fetch(PDO::FETCH_ASSOC);
        if($botControlLength == 0){
            echo('<meta http-equiv = "refresh" content = "5; URL = index.php">');
            echo "<div align = 'center'>";
            echo "Couldn't find the bot!";
            echo "</div>";
            $connect = null;
            die();
        }elseif ($user->id !== $botControlData["owner"]){
            echo('<meta http-equiv = "refresh" content = "5; URL = index.php">');
            echo "<div align = 'center'>";
            echo "You cannot edit other people's bot!";
            echo "</div>";
            $connect = null;
            die();
        }elseif (strlen(filter($_POST["prefix"])) > 50){
            echo("<center>Please enter a correct prefix!</center>");
            $connect = null;
            die();
        }elseif (strlen(filter($_POST["shortdesc"])) > 200){
            echo("<center>Please enter a short description of less than two hundred characters!</center>");
            $connect = null;
            die();
        }elseif (strlen(filter($_POST["detaileddesc"])) > 2000){
            echo("<center>Please enter a detailed description of less than two thousand characters!</center>");
            $connect = null;
            die();
        }elseif ((!empty(filter($_POST["website"]))) and (strlen(filter($_POST["website"])) > 300)){
            echo("<center>Please enter a correct website!</center>");
            $connect = null;
            die();
        }elseif ((!empty(filter($_POST["supserver"]))) and (strlen(filter($_POST["supserver"])) > 100)){
            echo("<center>Please enter a correct support server link!</center>");
            $connect = null;
            die();
        }elseif ((!empty(filter($_POST["invite"]))) and (strlen(filter($_POST["invite"])) > 300)){
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
            if(!empty($_POST["tags"])){
                $tags = "";
                foreach($_POST["tags"] as $one){
                    if ($tags == "") {
                        $tags = $tags . filter($one);
                    }else{
                        $tags = $tags . "," . filter($one);
                    }
                }
            }else{
                $tags = $botControlData["tags"];
            }
            $database = $connect->prepare("UPDATE bots SET library = ?, prefix = ?, shortdesc = ?, detaileddesc = ?, tags = ?, invite = ? WHERE id = ? LIMIT 1");
            $database->execute(array(
                filter($_POST["library"]),
                filter($_POST["prefix"]),
                filter($_POST["shortdesc"]),
                filter($_POST["detaileddesc"]),
                $tags,
                filter($_POST["invite"]),
                filter($_POST["id"]),
            ));
            $isSuccess = $database->rowCount();

            if ((empty(filter($_POST["website"]))) and (!empty(filter($_POST["supserver"])))) {
                $secData = $connect->prepare("UPDATE bots SET supserver = ? WHERE id = ? LIMIT 1");
                $secData->execute(array(
                    filter($_POST["supserver"]),
                    filter($_POST["id"]),
                ));
            } elseif((empty(filter($_POST["supserver"]))) and (!empty(filter($_POST["website"])))) {
                $secData = $connect->prepare("UPDATE bots SET website = ? WHERE id = ? LIMIT 1");
                $secData->execute(array(
                    filter($_POST["website"]),
                    filter($_POST["id"]),
                ));
            } elseif ((!empty(filter($_POST["supserver"]))) and (!empty(filter($_POST["website"])))) {
                $secData = $connect->prepare("UPDATE bots SET website = ?, supserver = ? WHERE id = ? LIMIT 1");
                $secData->execute(array(
                    filter($_POST["website"]),
                    filter($_POST["supserver"]),
                    filter($_POST["id"]),
                ));
            }

            if ($isSuccess > 0) { ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Discord Bot List - Edit A Bot!</title>
                    <link
                        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap"
                        rel="stylesheet"
                    />
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <meta http-equiv="Content-Language" content="en" />
                    <meta charset="utf-8" />
                    <?php require_once("navbar.php"); ?>
                    <style>
                        .success {
                            height: 100%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .success {
                            flex-direction: column;
                        }
                    </style>
                    <meta http-equiv = "refresh" content = "5; URL = bot.php?id=<?php echo(filter($_POST["id"])); ?>">
                </head>
                <body>
                <div class = "success">
                    <p>This bot successfully, edited! (ID: <?php echo(filter($_POST["id"])); ?>) You are being redirect soon!</p>
                </div>
                </body>
                </html>
          <?php  }
        
        $connect = null;
    } ob_end_flush(); ?>