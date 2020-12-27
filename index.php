<?php



ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', 'your');

define('OAUTH2_CLIENT_SECRET', 'YOUR');

$authorizeURL = 'https://discordapp.com/api/oauth2/authorize';

$tokenURL = 'https://discordapp.com/api/oauth2/token';

$apiURLBase = 'https://discordapp.com/api/users/@me';

$revokeURL = 'https://discordapp.com/api/oauth2/token/revoke';

session_start();



if(get('action') == 'login') {

  $params = array(

    'client_id' => OAUTH2_CLIENT_ID,

    'redirect_uri' => 'https://verify.exposit.xyz/',

    'response_type' => 'code',

    'scope' => 'identify guilds email'

  );

  // Redirect the user to Discord's authorization page

  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));

  die();

}

if(get('code')) {

    // Exchange the auth code for a token

    $token = apiRequest($tokenURL, array(

      "grant_type" => "authorization_code",

      'client_id' => OAUTH2_CLIENT_ID,

      'client_secret' => OAUTH2_CLIENT_SECRET,

      'redirect_uri' => 'https://verify.exposit.xyz/',

      'code' => get('code')

    ));

    $logout_token = $token->access_token;

    $_SESSION['access_token'] = $token->access_token;

    header('Location: ' . $_SERVER['PHP_SELF']);

  }

if(get('action') == 'logout') {

    apiRequest($revokeURL, array(

        'token' => session('access_token'),

        'client_id' => OAUTH2_CLIENT_ID,

        'client_secret' => OAUTH2_CLIENT_SECRET,

      ));

    unset($_SESSION['access_token']);

    header('Location: ' . $_SERVER['PHP_SELF']);

    die();

  }



function apiRequest($url, $post=FALSE, $headers=array()) {

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);

    if($post)

      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $headers[] = 'Accept: application/json';

    if(session('access_token'))

      $headers[] = 'Authorization: Bearer ' . session('access_token');

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    return json_decode($response);

  }

  function get($key, $default=NULL) {

    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;

  }

  function session($key, $default=NULL) {

    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;

  }

?>

<!DOCTYPE html>

<html lang="pt">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>login With Discord</title>

</head>

<style>

html,

body {

  background: #26262b;

  margin-left: 50px;

  margin-top: 150px;

  height: 50%;

  text-align: center;

  content-align: center;

  line-height: 1.15;

}

.testzao{

  color:yellow;

  font-family: Arial, Helvetica, sans-serif;



}

.discord-logo {

  display: block;

  margin-left: auto;

  margin-right: auto;

  width: 250px;

}



.wrapper-buttons {

  display: block;

  margin-left: auto;

  margin-right: auto;

  width: 250px;

}



.wrapper-buttons > * {

  margin-bottom: 10px;

}



.wrapper-buttons > *:first-child {

  margin-top: 10px;

}



.wrapper-buttons > *:last-child {

  margin-bottom: 0px;

}



.red-button,

.blue-button {

  display: inline-block;

  font-family: Whitney, "Open Sans", Helvetica, sans-serif;

  font-weight: 400;

  font-size: 11pt;

  border-radius: 3px;

  cursor: pointer;

  height: 45px;

  width: 250px;

  box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.2);

}



.blue-button {

  background-color: #7289da;

  border: 2px solid #7289da;

  color: #fff;

}



.red-button {

  background-color: red;

  border: 2px solid red;

  color: #fff;

}



.wrapper-nitro-buttons {

  margin: 0 auto !important;

  width: 60%;

  padding-top: 20px;

  padding-bottom: 20px;

  display: block;

}









.discount {

  display: inline;

  border: 1px solid hsla(0, 0%, 100%, 0.3);

  color: hsla(0, 0%, 100%, 0.75);

  border-radius: 3px;

  font-size: 12px;

  font-weight: 400;

  margin-left: 3px;

  padding: 3px;

  width: 50px;

}



.blue-button:hover,

.red-button:hover,

.black-nitro-button:hover,

.green-nitro-button:hover {

  transform: translateY(1px);

}



.blue-button:active,

.red-button:active,

.black-nitro-button:active,

.green-nitro-button:active {

  transform: translateY(2px);

}



.blue-button,

.red-button,

.black-nitro-button,

.green-nitro-button {

  outline: transparent !important;

}



</style>

<body>

  <br>

<div class="discord-logo">

  <img src="https://discordapp.com/assets/93608abbd20d90c13004925014a9fd01.svg">

</div>

<br>

<br>

<?php

if(session('access_token')) {

  $user = apiRequest($apiURLBase);

  echo '<h3 style="color:white; font-family:arial; font-size:40px;">Logged In</h3>';

  echo '  <div class="testzao">

  <h4>Bem Vindo, ' . $user->username . ' #'. $user->discriminator .'</h4> 

  

  <img src="https://cdn.discordapp.com/avatars/' . $user->id . '/' . $user->avatar . '.jpg"> 

  

  </div>';

     echo "<a href='verifica_login.php?submitt=1&username=" . $user->username . "&email=" . $user->email . "&avatar=https://cdn.discordapp.com/avatars/" . $user->id . "/" . $user->avatar . ".jpg'><button class='blue-button' name='submitt' type='submitt'>Pretende Continuar ?</button></a>";



  /*echo '<pre>';

    print_r($user);

  echo '</pre>';*/

} else {

  echo '<h3 style="color:white; font-family:arial; font-size:30px;">Not logged in</h3>';

  echo '

  <div class="wrapper-buttons">

  <a href="?action=login"><button class="blue-button">Login Através do  Discord</button></a>

  </dvi>';

}

?>

  

    

</body>

</html>