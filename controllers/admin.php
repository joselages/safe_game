<?php

$actions = [
    'login',
    'logout',
    'safes',
    'users'
];

if( empty($action) ){
    $action = 'login';
} else if(
    $action === 'login' &&
    isset($_POST['submit'])
){
    require('models/Access.php');

    $model= new Access();

    $result = $model->loginAdmin($_POST);

    if($result['isAdmin']){
        $_SESSION['user_id'] = $result['message']['user_id'];
        $_SESSION['admin'] = $result['message']['user_id'];
        header('Location:' . ROOT . '/admin/safes');
        die;
    }
} else if(
    $action === 'safes' &&
    isset($_SESSION['admin'])
){

    require('models/Safe.php');
    $model=new Safe();

    $safes = $model->adminGetAll();
} else if(
    $action === 'users' &&
    isset($_SESSION['admin']) &&
    $_SERVER['REQUEST_METHOD'] === 'DELETE' &&
    isset($id) &&
    is_numeric($id)
) {

    header('Content-Type:application/json');

    $id = intval($id);
    require('models/User.php');
    $model=new User();

    $result = $model->adminDelete($id);

    echo json_encode($result);
    die;
} else if(    
    $action === 'users' &&
    isset($_SESSION['admin'])
){
    require('models/User.php');
    $model=new User();

    $users = $model->adminGetAll();
} 

if(!in_array($action, $actions)){
    http_response_code(404);
    die('Not found...');
}

require('views/admin/'.$action.'.php');