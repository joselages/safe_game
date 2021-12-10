<?php

define('ROOT', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

$url_parts = explode('/', $_SERVER['REQUEST_URI']);

$controllers = [
    'login',
    'signup',
    'user',
    'safe',
    'create',
    'verification'
];

$controller = $url_parts[1];

if(empty($controller)){
    require('views/homepage.php');
    die;
}

$id = !empty($url_parts[2]) ? $url_parts[2] : '';

if(!in_array($controller, $controllers)){
    http_response_code(404);
    die('Not found');
}


require('controllers/'.$controller.'.php');
