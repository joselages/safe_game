<?php

$actions = [
    'show',
    'edit',
    'create'
];

require('models/User.php');
$model = new User();

if(is_numeric($action)){ //get by id

    $id = $action;
    $action = 'show';  
} else if( //editar user
    $action === 'edit'
){

    if(!is_numeric($id)){
        http_response_code(400);
        die('Bad request');
    }

} else if($action === 'create'){ //sign up

}else{ //ver o perfil do user q está logged

    $id = 1;
    $action = 'show';  

}



if(!in_array($action, $actions)){
    http_response_code(400);
    die('Bad request');
}



$user = $model->get($id);


require('views/user/'.$action.'.php');