<?php 
    require_once("functions.php");
    if(empty($_GET["id"])){
        header("Location: ./");
        die();
    }
   require_once("dbConnect.php");
   $getBotData = $connect->prepare("SELECT * FROM bots WHERE id = ?");
   $getBotData->execute(array(
       filter($_GET["id"])
   ));
   if($getBotData->rowCount() < 1){
       echo(json_encode(array("error" => 1)));
       $connect = null;
       die();
   }
   $botData = $getBotData->fetch(PDO::FETCH_ASSOC);
   echo(json_encode(array("error" => 0, "data" => $botData)));
   $connect = null;
?>