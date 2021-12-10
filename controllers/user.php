<?php

require('models/User.php');
$model = new User();

$id = $action;


$user = $model->get($id);

require('views/safe_list.php');