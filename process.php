<?php
    if((empty($_GET["action"])) and (empty($_GET["code"]))){
        header("Location: ./");
        die();
    }elseif(($_GET["action"] != "logout") and ($_GET["action"] != "login") and (empty($_GET["code"]))){
        header("Location: ./");
        die();
    }
    require_once("functions.php");
    require_once("navbar.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);
error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', '712682720664355109'); // OAUTH2 CLIENT ID for your bot.
define('OAUTH2_CLIENT_SECRET', '58Cz2Bh17w1i5MbQogGMelVu4tyoac0D'); // OAUTH2 CLIENT SECRET TOKEN for your bot.

$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$redirectURL = $url . '/process.php';
if (get('action') == 'login'){
    $params = array(
        'client_id' => OAUTH2_CLIENT_ID,
        'redirect_uri' => $redirectURL,
        'response_type' => 'code',
        'scope' => 'identify guilds'
    );

    header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
    die();
}

if(get('action') == 'logout'){
    session_destroy();
    unset($_SESSION["user"]);
    unset($_SESSION["access_token"]);
    header("Location: index.php");
}
if (get('code')){
    $token = apiRequest($tokenURL, array(
        "grant_type" => "authorization_code",
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
        'redirect_uri' => $redirectURL,
        'code' => get('code')
    ));
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;
    header('Location: ' . $_SERVER['PHP_SELF']);
}
if (session('access_token')){
    $_SESSION["user"] = session('access_token');
    header("Location: profile.php");
}
?>