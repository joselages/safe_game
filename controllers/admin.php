<?php

$actions = [
    'login',
    'logout',
    'safes',
    'users',
    'stats'
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
    isset($_SESSION['admin']) &&
    $_SERVER['REQUEST_METHOD'] === 'PUT' &&
    isset($id) &&
    is_numeric($id)
){
    $requests = [
        'verificationChange',
        'adminChange'
    ];

    header('Content-Type:application/json');
    $data = (array)json_decode(file_get_contents('php://input'));

    if(
        !isset($data['request']) ||
        !in_array($data['request'], $requests)
    ){
        http_response_code(400);
        echo json_encode([
            'isOk' => false,
            'message' => 'Bad request...'
        ]);
        die;
    }


    require('models/User.php');
    $model = new User();

    if($data['request'] === 'verificationChange'){
        $result = $model->adminVerificationChange($data);
    } else if($data['request'] === 'adminChange'){
        $result = $model->adminAdminChange($data);
    }


    echo json_encode( $result);
    die;
} else if(    
    $action === 'users' &&
    isset($_SESSION['admin'])
){
    require('models/User.php');
    $model=new User();

    $users = $model->adminGetAll();
} else if (
    $action === 'logout'
){
   unset( $_SESSION['admin'] );
   header('Location:/');
   die;
}

if(
    !in_array($action, $actions)
){
    http_response_code(404);
    die('Not found...');
}

if(
    $action !== 'login' &&
    !isset($_SESSION['admin'])
){
    header('Location:/admin/login');
    die;
}


require('views/admin/'.$action.'.php');