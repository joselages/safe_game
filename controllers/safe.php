<?php

$actions = [
    'show',
    'edit',
    'create'
];

require('models/Safe.php');
$model = new Safe();

if (
    !in_array($action, $actions)
) {
    http_response_code(404);
    die('Not found...');
}

if(
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


require('views/safe/'.$action.'.php');