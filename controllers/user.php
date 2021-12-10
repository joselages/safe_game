<?php

require('models/User.php');
$model = new User();

if(is_numeric($action)){ //get by id
    $id = $action;  
    
    $user = $model->get($id);

} else if( //editar user
    $action === 'edit' &&
    is_numeric($id)
){

    $user = $model->get($id);


    require('views/user/edit.php');
    exit;

} else { //ver o perfil do user q está logged

    // $id = $_SESSION['user_id'];
    $id = 1;  
    $user = $model->get($id);

}


require('views/user/show.php');