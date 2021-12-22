<?php

$actions = [
    'show',
    'edit',
    'create'
];

require('models/User.php');
$model = new User();

if ( is_numeric($action))  {
    $id = $action;
    $action = 'show';

    require('models/Safe.php');
    $safeModel = new Safe();

    $safes = $safeModel->getAllByUserId($id);

} else if ( $action === 'edit' ) {

    if (!$is_logged) {
        header('Location: ' . ROOT . '/login');
        die;
    }
    
    if (
        isset($_POST['submit'])
    ) {

        $_POST["user_id"] = $_SESSION['user_id'];

        $edit = $model->edit($_POST);
    }

    $id = $_SESSION['user_id'];
} else if ($action === 'create') { ////user/create

    if (isset($_POST['submit'])) {

        $result = $model->store($_POST);

        if ($result['isStored']) {

            require('./libs/php_mailer.php');

            $emailInfo['send_to'] = $result['user']['email'];
            $emailInfo['username'] = $result['user']['username'];
            $emailInfo['subject'] = 'Verification link';
            $emailInfo['content'] = '<p>Hello '.$result['user']['username'].'!<br>Please verify you account clicking on <a href="localhost/verification/'.$result['user']['verification_code'].'">this link</a></p>';

           $mailSent = sendMail($emailInfo);

            if($mailSent === false){
                $result['message'] = 'Can\'t send mail, please verify your email clicking <a href="localhost/verification/'.$result['user']['verification_code'].'"">here</a>';
            }

            http_response_code(202);
        }
    } else if (
        $is_logged &&
        !isset($_POST['submit'])
    ) {
        header('Location: ' . ROOT . '/user');
        die;
    }
} else if( empty($id) ) { //ir para a raiz -> /

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

if (
    !in_array($action, $actions)
) {
    http_response_code(404);
    die('Not found...');
}

if ($action !== 'create') {
    $result = $model->get($id);

    $username =  'My';
    if(
        !$is_logged &&
        ( $result['status'] === false && $result['user']['user_id'] !== $_SESSION['user_id'] )
    ){
        $username = $result['user']['user_id'];
    }
}



require('views/user/' . $action . '.php');
