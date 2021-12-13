<?php

if (isset($_POST['submit'])) {

    require('models/Access.php');

    $model= new Access();

    $result = $model->login($_POST);
    
    if($result['isLogged']){
        $_SESSION['user_id'] = $result['message']['user_id'];
        $_SESSION['username'] = $result['message']['username'];
        header('Location:' . ROOT . '/user');
        die;
    }

}

require('views/login.php');
