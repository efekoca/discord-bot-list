<?php ob_start();
require_once("functions.php");
require_once("dbConnect.php");
define("discordAPI", "https://discord.com/api/users/@me");

if (empty($_SESSION["user"])) {
    echo('<meta http-equiv = "refresh" content = "5; URL = process.php?action=login">');
    echo "<div align = 'center'>";
    echo "Please log in!";
    echo "</div>";
    die();
} else {
    $user = apiRequest(discordAPI);
    $nitro = array();

    if (isset($user->premium_type)) {
        if ($user->premium_type == "1") {
            array_push($nitro, "Classic Nitro");
        } elseif ($user->premium_type == 2) {
            array_push($nitro, "Discord Nitro");
        } else {
        }
    }

    if (count($nitro) != 0) {
        $premiumNitroImg = array("https://i.imgur.com/AwvNEXW.png");
        $nitroImg = array("https://i.imgur.com/fhyZEaU.png");
        if (in_array("Discord Nitro", $nitro)) {
            $formattedNitro = preg_replace(array("/Discord Nitro/"), $premiumNitroImg, $nitro);
        } else {
            $formattedNitro = preg_replace(array("/Classic Nitro/"), $nitroImg, $nitro);
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Discord Bot List - <?php echo($user->username . "#" . $user->discriminator); ?></title>
        <link rel = "stylesheet" type = "text/css" href = "css/style.css">
        <link rel = "stylesheet" type = "text/css" href = "css/bot.css">
        <?php $getClass = new getData(); ?>
        <link rel = "icon" type="image/png" href="<?php echo($getClass->getBotAvatar($user->id,'png') . "?size=64"); ?>" />
        <?php include("navbar.php");

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://discord-bot-list-api.glitch.me/?id={$user->id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_REFERER => "google.com",
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36"
        ));

        $chEnd = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if (!$err) {
            $json = json_decode($chEnd);
            $flags = array("/DISCORD_EMPLOYEE/", "/DISCORD_PARTNER/", "/HYPESQUAD_EVENTS/", "/BUGHUNTER_LEVEL_1/", "/HOUSE_BRAVERY/", "/HOUSE_BRILLIANCE/", "/HOUSE_BALANCE/", "/EARLY_SUPPORTER/", "/TEAM_USER/", "/SYSTEM/", "/BUGHUNTER_LEVEL_2/", "/VERIFIED_BOT/", "/VERIFIED_DEVELOPER/");
            $formattedFlags = array("Discord Staff", "Discord Partner", "Hypesquad Events", "Bug Hunter (Level 1)", "House Bravery", "House Brillance", "House Balance", "Early Supporter", "Team User", "System", "Bug Hunter (Level 2)", "Verified Bot", "Verified Bot Developer");
            $imgFlags = array("https://i.imgur.com/adjIc0L.png", "https://i.imgur.com/TFY4AIs.png", "https://i.imgur.com/D3I3MiR.png", "https://i.imgur.com/9O1pqoJ.png", "https://i.imgur.com/epnRwp4.png", "https://i.imgur.com/N4XsUNI.png", "https://i.imgur.com/uFMkF8H.png", "https://i.imgur.com/2L69JgZ.png", "https://i.imgur.com/65pfMH0.png", "https://i.imgur.com/htNGKr3.png", "https://i.imgur.com/a4gJcQq.png", "https://i.imgur.com/AhpZDB1.png", "https://i.imgur.com/3IVxkBs.png");
            $reformattedImgFlags = preg_replace($flags, $imgFlags, $json->flags);
            $reformattedFlags = preg_replace($flags, $formattedFlags, $json->flags);
        } else {
        }

       ?>
        <style>
            .badges {
                width: 40px;
                margin-right: 8px;
            }

        </style>
    </head>
    <body>
        <div align = "center">
        <h4>Welcome, <?php echo($user->username . "#" . $user->discriminator); ?> </h4>
        <img src="<?php echo(getAvatar($user->id, $user->avatar)); ?>" alt = "<?php echo($user->username . "#" . $user->discriminator); ?>" style = "border-radius: 200px; height: 100px; width: 100px;"><br /><br /><br />
            <div>
            <?php
            echo("<img src = '". "https://i.imgur.com/2vWcoe5.png" . "' title = '" . "MFA Enabled" . "' class = 'badges' style = 'width: 45px;'>");
            if((count($nitro) == 0) and (count($reformattedFlags) == 0)){
                echo("No badge found!");
            }elseif((count($nitro) == 0) and (count($reformattedFlags) >= 1)){
                foreach($reformattedImgFlags as $key => $oneImg){
                    echo("<img src = '". $oneImg . "' title = '" . $reformattedFlags[$key] . "' class = 'badges'>");
                }
            }elseif((count($nitro) >= 1) and (count($reformattedFlags) >= 1)){
                foreach($reformattedImgFlags as $key => $oneImg){
                    echo("<img src = '". $oneImg . "' title = '" . $reformattedFlags[$key] . "' class = 'badges'>");
                }
                if($nitro[0] == "Discord Nitro"){
                    echo("<img src = '". $premiumNitroImg[0] . "' title = '" . "Discord Premium Nitro" . "' class = 'badges'>");
                    echo("<img src = '". $nitroImg[0] . "' title = '" . "Discord Nitro" . "' class = 'badges'>");
                }else{
                    echo("<img src = '". $nitroImg[0] . "' title = '" . "Discord Nitro" . "' class = 'badges'>");
                }
            }else{
                foreach($nitro as $one){
                    if($one == "Discord Nitro"){
                        echo("<img src = '". $premiumNitroImg[0] . "' title = '" . "Discord Premium Nitro" . "' class = 'badges'>");
                        echo("<img src = '". $nitroImg[0] . "' title = '" . "Discord Nitro" . "' class = 'badges'>");
                    }else{
                        echo("<img src = '". $nitroImg[0] . "' title = '" . "Discord Nitro" . "' class = 'badges'>");
                    }
                }
            }
            ?>
            </div>
        </div>
        <br />

        <?php
            $bots = $connect->prepare("SELECT * from bots WHERE owner = ?");
            $bots->execute(array($user->id));
            $botsLength = $bots->rowCount();
            if ($botsLength > 0) { ?>
            <center><h3>Bot<?php echo($botsLength == 1 ? "" : "s"); ?> of you:</h3></center>
                <div class = "cards">
                    <?php foreach ($bots as $oneBot) { ?>
                        <div class = "bot-card">
                            <div class = "top">
                                <div class = "img-bot">
                                    <img style = "cursor: pointer; height: 134px; width: 134px; border-radius: 200px;" src = "<?php echo($getClass->getBotAvatar($oneBot["id"])); ?>">
                                    <img style = "position: relative; left: -6%; bottom: -85px;" src = "<?php echo($getClass->getStatus($oneBot["id"])); ?>" width = "36" height = "36">
                                    <br /><div class = "bot-name-short">
                                        <p style="font-size: 13px;"><?php echo($oneBot["name"]); ?></p>
                                        <p style = "font-size 11px;"><?php echo($oneBot["shortdesc"]); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class = "bottom">
                                <div class = "bot-buttons" style = "bottom: 0;">
                                    <a href = "/vote.php?id=<?php echo($oneBot["id"]); ?>" class = "bot-button" style = "background: #f8c291;"><?php echo($oneBot["vote"] ? $oneBot["vote"] : "0"); ?> Upvote!</a><br /><br />
                                    <a href = "/bot.php?id=<?php echo($oneBot["id"]); ?>" class = "bot-button">Visit</a>
                                    <a href = "<?php echo($oneBot["invite"]); ?>" class = "bot-button">Invite</a>
                                    <?php if (($oneBot["website"] != null) and ($oneBot["website"] != "")) { ?>
                                        <a href = "<?php echo($oneBot["website"]); ?>" class = "bot-button">Website</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
         <?php
            } else {
                echo("<center>Couldn't find a bot!</center>");
            }
        ?>
    </body>
    </html>
    <?php $connect = null; ob_end_flush(); ?>
    <?php } ?>