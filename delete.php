<?php require_once("functions.php");
    if(empty($_SESSION["user"])){
        echo('<meta http-equiv = "refresh" content = "5; URL = process.php?action=login">');
        echo "<div align = 'center'>";
        echo "Please log in!";
        echo "</div>";
        die();
    }elseif(empty($_GET["id"])) { ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Discord Bot List - Delete A Bot!</title>
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
            <link rel="stylesheet" type="text/css" href = "css/form.css">
            <link rel="stylesheet" type="text/css" href = "css/style.css">
        </head>
        <body>
          <?php require("navbar.php"); ?>
        <center>
            <div class = "form">
                <h3> Delete a bot! </h3>
                <form action="delete.php" method="get">
                    <input type = "text" placeholder = "Please enter ID." name = "id" class = "inp" required>
                            </br></br></br><button type="submit"> Submit
          <span></span>
        </button>
                </form>
            </div>
        </center>
        </body>
        </html>

<?php die();
    } else {
        require_once("dbConnect.php");
        define("discordAPI", "https://discord.com/api/users/@me");
        $getBot = $connect->prepare("SELECT * from bots WHERE id = ?");
        $getBot->execute(array(filter($_GET["id"])));
        $botLength = $getBot->rowCount();

        if ($botLength == 0) {
            echo('<meta http-equiv = "refresh" content = "5; URL = index.php">');
            echo "<div align = 'center'>";
            echo "No bot found!";
            echo "</div>";
            $connect = null;
            die();
        } else {
            $user = apiRequest(discordAPI);
            $ownerControl = $connect->prepare("SELECT * from bots WHERE owner = ? AND id = ?");
            $ownerControl->execute(array(
                filter($user->id),
                filter($_GET["id"]),
            ));
            $ownerControlLength = $ownerControl->rowCount();

            if ($ownerControlLength == 0) {
                echo('<meta http-equiv = "refresh" content = "5; URL = index.php">');
                echo "<div align = 'center'>";
                echo "No bot found!";
                echo "</div>";
                $connect = null;
                die();
            } else {
                $deleteBot = $connect->prepare("DELETE FROM bots WHERE id = ? LIMIT 1");
                $deleteBot->execute(array(filter($_GET["id"])));
            }
        }
    }
$connect = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Discord Bot List - Delete A Bot!</title>
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
</head>
<body>
    <div class = "success">
        <p>This bot successfully, deleted our system! (ID: <?php echo(filter($_GET["id"])); ?>)</p>
    </div>
</body>
</html>
