<?php 

$navArray = [
    '/' => 'Homepage',
    '/safe/create' => 'Create Safe',
    '/user' => 'Profile',
    '/user/create' => 'Sign up',
    '/login' => 'Login',
    '/logout' => 'Logout',
];

?>

    <nav class="navigation">
        <ul>
            <?php
                foreach($navArray as $key => $value){
                if(
                    (!$is_logged &&
                    ($key === '/user' || $key === '/logout')) ||
                    ($is_logged &&
                    ($key === '/login' || $key === '/user/create'))
                ){
                    continue;
                }

                $isActive = $_SERVER['REQUEST_URI'] === $key ? '-active' : '' ;

                echo  '<li class="nav_item '.$isActive.'"><a href="'.$key.'">'.$value.'</a></li>';
                }
            ?>
        </ul>
    </nav>