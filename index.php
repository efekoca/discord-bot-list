<?php require_once("functions.php"); ?>
<?php
    if(empty($_GET["page"])){
        $page = 1;
    }elseif(is_int($_GET["page"])){
        $page = filter($_GET["page"]);
    }else{
        $page = 1;
    } ?>

<!DOCTYPE html>

<html>
<head>
    <title>Discord Bot List</title>
    <link rel = "stylesheet" type = "text/css" href = "css/style.css">
    <link rel = "stylesheet" type = "text/css" href = "css/bot.css" />
    <?php require_once("navbar.php"); require_once("dbConnect.php"); ?>
    <style>
        .inp{
            border: none;
            outline: none;
            background: none !important;
            width: 290px;
            height: 38px;
            border: solid 2px #747d8c;
            border-radius: 4px 2px 4px 2px;
            padding: 3px 2px;
            box-shadow: 1.3px 4px 3px;
            transition: 0.2s ease-in-out;
            text-align: center;
        }
        form{
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        .activePage, .deactivePage a{
            text-decoration: none;
        }

        .activePage{
            color: #60a3bc;
        }

        .deactivePage a{
            color: #0984e3;
        }
        .search{
            margin-top: 50px;
        }
    </style>
</head>

<body>

<?php if((($_GET) and (empty($_GET["botname"]))) or (!$_GET)){ ?>
    <form action = "index.php" method = "get" class="search">
        <input type = "text" placeholder = "Enter a bot name..." name = "botname" class = "inp" required> &nbsp;&nbsp;
        <button type="submit"> Search
          <span></span>
        </button>
    </form>

    <?php
    $query = $connect->query("SELECT * from bots");
    $query->execute();
    $queryLength = $query->rowCount();
    $rightAndLeftButtonLength = 2;
    $perPageQueryLength = 2;
    $numberOfRecordsToBeginPagination =	($page * $perPageQueryLength) - $perPageQueryLength;
    $foundPagesLength = ceil($queryLength / $perPageQueryLength);
    if(($queryLength > 0) and ($page > $foundPagesLength) or (!is_numeric($page))) {
        $connect = null;
        header("Location: index.php");
        die();
    }
    $fetchBots = $connect->prepare("SELECT * FROM bots ORDER BY orderID ASC LIMIT {$numberOfRecordsToBeginPagination}, {$perPageQueryLength}");
    $fetchBots->execute();
    $bots = $fetchBots->fetchAll(PDO::FETCH_ASSOC);

    if ($queryLength != 0) {
        $getClass = new getData();
        ?>
    <div class="cards">
        <?php foreach($bots as $oneBot){ ?>
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
        <div class="page" align="center">
            <?php
            if ($page > 1) {
                echo("<span class='deactivePage'><a href='index.php'><<</a></span>");
                echo(" <span class='deactivePage'><a href='index.php?page=" . ($page - 1) . "'><</a></span>");
            }

            for ($i = $page - $rightAndLeftButtonLength; $i <= $page + $rightAndLeftButtonLength; $i++) {
                if (($i > 0) and ($i <= $foundPagesLength)) {
                    if ($page == $i) {
                        echo(" <span class='activePage'>" . $i . "</span>");
                    } else{
                        echo(" <span class='deactivePage'><a href='index.php?page=" . $i ."'>" . $i . "</a></span>");
                    }
                }
            }

            if ($page != $foundPagesLength) {
                echo(" <span class='deactivePage'><a href='index.php?page=" . ($page + 1) . "'>></a></span>");
                echo(" <span class='deactivePage'><a href='index.php?page=" . $foundPagesLength . "'>>></a></span>");
            }
            ?>
        </div><br />
        <?php
    } else {
        echo ("<div style='margin-top: 100px;'><center> <b> Couldn't find a bot! </b> </center></div>");
    }
    ?>

<?php } elseif (($_GET) and (!empty($_GET["botname"]))) { ?>
    <form action = "index.php" method = "get">
        <input type = "text" placeholder = "Enter a bot name..." name = "botname" class = "inp" required> &nbsp;&nbsp;
        <button type="submit"> Search
                  <span></span>
                </button>
   </form>
    <?php
    $filteredName = filter($_GET["botname"]);
    $formattedName = "%{$filteredName}%";
    $query = $connect->prepare("SELECT * from bots WHERE name LIKE ?");
    $query->execute(array($formattedName));
    $queryLength = $query->rowCount();
    if($queryLength != 0){
        $bots = $query->fetchAll(PDO::FETCH_ASSOC);
        $getClass = new getData(); ?>
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

<?php } ?>

<?php $connect = null; ?>

</body>
</html>
