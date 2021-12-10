<?php

if(empty($id)){
    http_response_code(400);
    die('Bad request');
}


require('views/verification_email.php');