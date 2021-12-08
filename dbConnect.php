<?php
    if((basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) or (basename($_SERVER["PHP_SELF"]) == substr(basename(__FILE__), 0, (strlen(basename(__FILE__)) - 4)))){
            header("Location: ./");
            die();
        }
    try {
        $connect = new PDO("mysql:host=localhost;dbname=databasename;charset=UTF8", "username", "password");
    } catch(PDOException $err) {
        echo("<center>" . $err->getMessage() . "</center>");
        $connect = null;
        exit();
    }
?>
