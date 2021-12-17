<?php

$actions = [
    'show',
    'edit',
    'create'
];

require('models/Safe.php');
$model = new Safe();

if(
    $_SERVER['REQUEST_METHOD'] === "GET" &&
    is_numeric($action)
){
    $id = $action;
    $action = "show";

    $result = $model->get($id);

    if(
        $result['isOk']
    ){
        $_SESSION['safe'] = $result['safe'];
    } else {
        $_SESSION['safe'] = [];
    }
} else if(
    $action === "create" &&
    isset($_POST['request'])
){
        header('Content-Type:application/json');

        $data = $_POST;

        if($is_logged){
            $data['user_id'] = $_SESSION['user_id'];
        }

        if(
            isset($_FILES['picture']) && 
            $is_logged
        ){
            $picture = $_FILES['picture'];
        }

        if(isset($picture)){
            $result = $model->store($data, $picture);
        } else {
            $result = $model->store($data);
        }

        echo json_encode($result);
        die;
}


if (
    !in_array($action, $actions)
) {
    http_response_code(404);
    die('Not found...');
}


require('views/safe/'.$action.'.php');