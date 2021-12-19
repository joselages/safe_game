<?php

$actions = [
    'show',
    'edit',
    'create'
];

require('models/User.php');
$model = new User();

if (
    is_numeric($action)
) { //get by id
    $id = $action;
    $action = 'show';

    require('models/Safe.php');
    $safeModel = new Safe();

    $safes = $safeModel->getAllByUserId($id);
    var_dump($safes);
} else if (
    $action === 'edit'
) {

    if (!$is_logged) {
        header('Location: ' . ROOT . '/login');
        die;
    }
    
    if (
        isset($_POST['submit'])
    ) {

        $_POST["user_id"] = $_SESSION['user_id'];

        $result = $model->edit($_POST);

        $_SESSION['username'] = $_POST["username"];
    }



    $id = $_SESSION['user_id'];
} else if ($action === 'create') { //sign up

    if (isset($_POST['submit'])) {
        $result = $model->store($_POST);

        if ($result['isStored']) {
            http_response_code(202);
        }
    } else if (
        $is_logged &&
        !isset($_POST['submit'])
    ) {
        header('Location: ' . ROOT . '/user');
        die;
    }
} else { //ir para a raiz -> /

    if (
        !$is_logged
    ) {
        header('Location: ' . ROOT . '/login');
        die;
    } else {
        $id = $_SESSION['user_id'];
        $action = 'show';

        require('models/Safe.php');
        $safeModel = new Safe();
    
        $safes = $safeModel->getAllByUserId($id);
    }
}



if ($action !== 'create') {

    $result = $model->get($id);
}

if (
    !in_array($action, $actions)
) {
    http_response_code(404);
    die('Not found...');
}

require('views/user/' . $action . '.php');
