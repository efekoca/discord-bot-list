<?php ob_start();
require_once("functions.php");
require_once("dbConnect.php");

if(empty($_SESSION["user"])){
    echo('<meta http-equiv = "refresh" content = "5; URL = process.php?action=login">');
    echo "<div align = 'center'>";
    echo "Please log in!";
    echo "</div>";
}else{
    if(empty($_GET["id"])){
        header("Location: index.php");
        die();
    }else{
        $database = $connect->prepare("SELECT * from bots WHERE id = ?");
        $database->execute(array(filter($_GET["id"])));
        $control = $database->rowCount();
        $data = $database->fetch(PDO::FETCH_ASSOC);
        $getClass = new getData();

        if($control == 0){
            header("Location: index.php");
            $connect = null;
            die();
        }else{
            define("discordAPI", "https://discord.com/api/users/@me");
            $user = apiRequest(discordAPI);
            $time = time();
            $id = $user->id;
            $botId = filter($_GET["id"]);
            if (file_exists("voters.txt")){
                $read = fopen("voters.txt", "r");
                $content = fread($read, filesize("voters.txt"));
                fclose($read);
                $explodedContent = explode(",", $content);
                $pattern = "/{$id}.+/u";
                $control = 0;
                foreach($explodedContent as $one){
                    if($control > 0){
                        break;
                    }
                    if(($one == null) or ($one == "")){
                        continue;
                    }else{
                        $preg = preg_match($pattern, $one);
                        if($preg == 0){
                            continue;
                        }else{
                            preg_match($pattern, $one, $newArr);
                            $control++;
                        }
                    }
                }
                if($control > 0){
                    $explodeUser = explode("-", $newArr[0]);
                    if($explodeUser[2] == $botId){
                        $getTime = intval($explodeUser[1]);
                        $controlTime = time() - 43200;
                        if ($controlTime < $getTime){
                            echo('<meta http-equiv = "refresh" content = "5; URL = index.php">');
                            echo "<div align = 'center'>";
                            echo "Wops! You can't vote yet! You can vote only once every twelve (12) hours.";
                            echo "</div>";
                            $connect = null;
                            die();
                        }else{
                            $file = fopen("voters.txt", "w");
                            $vote = $connect->prepare("UPDATE bots SET vote = vote + 1 WHERE id = ?");
                            $vote->execute(array(filter($_GET["id"])));
                            foreach($explodedContent as $one){
                                if(($one == "{$id}-{$getTime}-{$botId}") or ($one == "") or ($one == null)) {
                                    continue;
                                }else{
                                    fwrite($file, $one . ",");
                                }
                            }
                            fwrite($file, "{$id}-{$time}-{$botId}");
                            fclose($file);
                        }
                    }else{
                        $file = fopen("voters.txt", "w");
                        $vote = $connect->prepare("UPDATE bots SET vote = vote + 1 WHERE id = ?");
                        $vote->execute(array(filter($_GET["id"])));
                        foreach($explodedContent as $one) {
                            if(($one == "") or ($one == null)){
                                continue;
                            }else{
                                fwrite($file, $one . ",");
                            }
                        }
                        fwrite($file, "{$id}-{$time}-{$botId},");
                        fclose($file);
                    }
                }else{
                    $file = fopen("voters.txt", "w");
                    $vote = $connect->prepare("UPDATE bots SET vote = vote + 1 WHERE id = ?");
                    $vote->execute(array(filter($_GET["id"])));
                    foreach($explodedContent as $one) {
                        if(($one == "") or ($one == null)){
                            continue;
                        }else{
                            fwrite($file, $one . ",");
                        }
                    }
                    fwrite($file, "{$id}-{$time}-{$botId},");
                    fclose($file);
                }
            }else{
                $file = fopen("voters.txt", "w");
                $vote = $connect->prepare("UPDATE bots SET vote = vote + 1 WHERE id = ?");
                $vote->execute(array(filter($_GET["id"])));
                touch("voters.txt");
                $file = fopen("voters.txt", "a");
                fwrite($file , "{$id}-{$time}-{$botId},");
                fclose($file);
            } ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Discord Bot List - <?php echo($data["name"]); ?></title>
                <link rel = "icon" type="image/png" href="<?php echo($getClass->getBotAvatar(filter($_GET['id']), 'png') . "?size=64"); ?>" />
                <?php require("navbar.php"); ?>
                <style>
                    .success{
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .success{
                        flex-direction: column;
                    }
                </style>
            </head>
            <body>
            <div class = "success">
                <p>Successfully voted! (<?php echo($data["name"]); ?>)</p>
            </div>
            </body>
            </html>
<?php
        }
    }

}
$connect = null;
ob_end_flush();
?>