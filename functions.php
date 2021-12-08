<?php session_start(); ob_start();
    if((basename($_SERVER["PHP_SELF"]) == basename(__FILE__)) or (basename($_SERVER["PHP_SELF"]) == substr(basename(__FILE__), 0, (strlen(basename(__FILE__)) - 4)))){
        header("Location: ./");
        die();
    }
$url = ""; // URL of your site.
if(!file_exists("voters.txt")){
    touch("voters.txt");
}
function getAvatar($userId, $url, $format = null){
    if(empty($userId)){
        return "Param 'userId' is empty";
    }elseif(empty($url)){
        return "https://i.imgur.com/eV73L2N.png";
    }else{
        if(isset($format)){
            $formattedURL = $url . "." . $format;
        }else{
            if(substr($url, 0, 2) == "a_"){
                $formattedURL = $url . ".gif";
            }else{
                $formattedURL = $url . ".png";
            }
        }
    }
    $pureLink = "https://cdn.discordapp.com/avatars/{$userId}/";
    $result = $pureLink . $formattedURL;
    return $result;

}
function apiRequest($url, $post = false, $headers = array()){
    $ch = curl_init($url);
    curl_setopt_array($ch, array(CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, CURLOPT_RETURNTRANSFER => true));
    $response = curl_exec($ch);
    if ($post)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $headers[] = 'Accept: application/json';
    if (session('access_token'))
        $headers[] = 'Authorization: Bearer ' . session('access_token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    return json_decode($response);
}
function get($key, $default = null){
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
function session($key, $default = null){
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}
function filter($param){
    $a = trim($param);
    $b = strip_tags($a);
    $c = htmlspecialchars($b, ENT_QUOTES);
    return $c;
}
function api($id){
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "", // API URL so as to get to bot data.
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_REFERER => "google.com",
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36"
    ));
    $chEnd = curl_exec($ch);
    curl_close($ch);
    return $chEnd;
}
class getData{
    public function getBotAvatar($id, $format = null){
            $api = api($id);
            $json = json_decode($api);
            $avatarData = $json->user->avatar;
            if(empty($id)){
                return "Param 'id' is empty";
            }elseif (empty($avatarData)){
                return "Param 'url' is empty";
            }else{
                if(isset($format)){
                    $formattedURL = $avatarData . "." . $format;
                }else{
                    if (substr($avatarData, 0, 2) == "a_") {
                        $formattedURL = $avatarData . ".gif";
                    }else{
                        $formattedURL = $avatarData . ".png";
                    }
                }
            }
            $pureLink = "https://cdn.discordapp.com/avatars/{$id}/";
            $result = $pureLink . $formattedURL;
            return $result;
    }
    public function getBotName($id){
        $api = api($id);
        $json = json_decode($api);
        $name = $json->user->username;
        if (empty($id)) {
            return "Param 'id' is empty";
        } else {
            return $name;
        }
    }
    public function getStatus($id){
        $api = api($id);
        $json = json_decode($api);
        $status = $json->presence->status;
        if(empty($id)){
            return "Param 'id' is empty";
        }else{
            if($status == "online"){
                $formattedStatus = "https://cdn.discordapp.com/emojis/693391171174662154.png?v=1";
            }elseif ($status == "dnd"){
                $formattedStatus = "https://cdn.discordapp.com/emojis/703322241525481563.png?v=1";
            }elseif($status == "idle"){
                $formattedStatus = "https://cdn.discordapp.com/emojis/703238129188077589.png?v=1";
            }else{
                $formattedStatus = "https://cdn.discordapp.com/emojis/703238195554287737.png?v=1";
            }
            return $formattedStatus;
        }
    }
    public function botControl($id){
        $api = api($id);
        $json = json_decode($api);
        $isBot = $json->user->bot;
        if($isBot == 1){
            return "yes";
        }else{
            return "no";
        }
    }
} ?>