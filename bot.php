<?php
require_once("functions.php");
require_once("dbConnect.php");

if (!$_GET) {
    header("Location: index.php");
    exit();
} elseif (!empty($_GET["id"])) {

        $getBot = $connect->prepare("SELECT * from bots WHERE id = ?");
        $getBot->execute(array(filter($_GET["id"])));
        $botLength = $getBot->rowCount();
        $getBotDetails = $getBot->fetch(PDO::FETCH_ASSOC);

        if ($botLength == 0) {
            header("Location: index.php");
            $connect = null;
            die();
        } else { 
            $getClass = new getData();
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Discord Bot List - <?php echo($getBotDetails["name"]); ?></title>
                <link rel = "icon" type="image/png" href="<?php echo($getClass->getBotAvatar(filter($_GET['id']), 'png') . "?size=64"); ?>" />
                <link
                    href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap"
                    rel="stylesheet"
                />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <meta http-equiv="Content-Language" content="en" />
                <meta charset="utf-8" />
                <?php require_once("navbar.php"); ?>
                <link rel = "stylesheet" type = "text/css" href = "css/bot.css" />
            </head>
            <body>
            <div class = "background">
                    <div class = "bot-card">
                        <div class = "top">
                            <div class = "img-bot">
                                <img style = "cursor: pointer; height: 134px; width: 134px; border-radius: 200px;" src = "<?php echo($getClass->getBotAvatar($getBotDetails["id"])); ?>">
                                <img style = "position: relative; left: -8%; bottom: -90px;" src = "<?php echo($getClass->getStatus($getBotDetails["id"])); ?>" width = "36" height = "36">
                                <div class = "bot-name-short">
                                    <p style="font-size: 21px;"><?php echo($getBotDetails["name"]); ?></p>
                                    <p style = "font-size: 15px;"><?php echo($getBotDetails["library"]); ?></p>
                                    <p><?php echo($getBotDetails["shortdesc"]); ?></p>
                                </div>
                            </div>
                             <div class = "detailed-desc">
                                <p><?php echo($getBotDetails["detaileddesc"]); ?></p>
                            </div>
                        </div>

                    <div class = "bottom">
                        <div class = "bot-buttons" style = "bottom: 0;">
                            <p class = "bot-button"><?php echo("Prefix: " . $getBotDetails["prefix"]); ?></p>
                            <a href = "/vote.php?id=<?php echo($getBotDetails["id"]); ?>" class = "bot-button" style = "background: #f8c291;"><?php echo($getBotDetails["vote"] ? $getBotDetails["vote"] : "0"); ?> Upvote!</a><br /><br />
                            <a href = "/bot.php?id=<?php echo($getBotDetails["id"]); ?>" class = "bot-button">Visit</a>
                            <a href = "<?php echo($getBotDetails["invite"]); ?>" class = "bot-button">Invite</a>
                            <?php if (($getBotDetails["website"] != null) and ($getBotDetails["website"] != "")) { ?>
                            <a href = "<?php echo($getBotDetails["website"]); ?>" class = "bot-button">Website</a>
                        </div>
                    </div>
                </div>
              </div>
          </body>
         </html>
<?php } ?>
<?php } ?>
<?php
} elseif (!empty($_GET["certificated"])) {
    if ($_GET["certificated"] == "yes") {
        $prepareForCertificated = $connect->prepare("SELECT * from bots WHERE certificate = ?");
        $prepareForCertificated->execute(array("yes"));
        $prepareForCertificatedLength = $prepareForCertificated->rowCount();

        if ($prepareForCertificatedLength == 0) {
            echo("<center>No certificated bot found!</center>");
            $connect = null;
            die();
        } else {
            $bots = $prepareForCertificated->fetchAll(PDO::FETCH_ASSOC);
            $getClass = new getData(); ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Discord Bot List - Certificated Bots</title>
                <link
                        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap"
                        rel="stylesheet"
                />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <meta http-equiv="Content-Language" content="en" />
                <meta charset="utf-8" />
                <?php require_once("navbar.php"); ?>
                <link rel = "stylesheet" type = "text/css" href = "css/bot.css" />
            </head>
        <body>
            <center><p>Certificated Bots:</p></center><br /s>
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
        </body>
            </html>
  <?php }
    } else {
        header("Location: index.php");
        $connect = null;
        die();
    }
} else {
    header("Location: index.php");
    $connect = null;
    die();
}

$connect = null;

?>