<?php

$actions = [
    'show',
    'edit',
    'create'
];

require('models/User.php');
$model = new User();


$is_logged = isset($_SESSION['user_id']);

if (
    is_numeric($action)
) { //get by id
    $id = $action;
    $action = 'show';
} else if ( //editar user
    $action === 'edit'
) {

    if (!$is_logged) {
        header('Location: ' . ROOT . '/login');
        die;
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
    }
}



if ($action !== 'create') {

    $user = $model->get($id);
}

if (
    !in_array($action, $actions)
) {
    http_response_code(404);
    die('Not found...');
}

require('views/user/' . $action . '.php');
