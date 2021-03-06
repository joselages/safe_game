<?php

define('ROOT', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
define("CONFIG",parse_ini_file('.env'));


session_start();
 $is_logged = isset($_SESSION['user_id']);
// session_destroy();

$url_parts = explode('/', $_SERVER['REQUEST_URI']);

$controllers = [
    'admin',
    'login',
    'user',
    'safe',
    'verification', 
    'requests',
    'logout'
];

$host = $_SERVER['HTTP_HOST'];
$controller = !empty($url_parts[1]) ? $url_parts[1] : '';
$action = !empty($url_parts[2]) ? $url_parts[2] : '';
$id = !empty($url_parts[3]) ? $url_parts[3] : '';


if (empty($controller)) {

    require('models/Safe.php');
    $model=new Safe();

    $safes = $model->getAllPublic();

    require('views/homepage.php');
    die;
}


if (!in_array($controller, $controllers)) {
    http_response_code(404);
    die('Not found');
}


require('controllers/' . $controller . '.php');
