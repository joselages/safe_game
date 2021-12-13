<?php

$id = $action;

if(empty($id)){
    http_response_code(400);
    die('Bad request');
}

require('models/User.php');
$model = new User();

$result = $model->verifyUser($id);

require('views/verification_email.php');