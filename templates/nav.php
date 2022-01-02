<?php 

$navArray = [
    '/' => 'Homepage',
    '/safe/create' => 'Create Safe',
    '/user' => 'Profile',
    '/user/create' => 'Sign up',
    '/login' => 'Login',
    '/logout' => 'Logout',
];

$adminNav=[
    '/admin/login' => 'Admin Login',
    '/admin/safes' => 'Admin Safes',
    '/admin/users' => 'Admin Users',
    '/admin/stats' => 'Admin Stats',
    '/admin/logout' => 'Admin Logout',
]

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

                    $isActive = $_SERVER['REQUEST_URI'] === $key ? 'aria-current="page"' : '' ;

                    echo  '<li class="nav_item" '.$isActive.'><a href="'.$key.'">'.$value.'</a></li>';
                }
            ?>
        </ul>

        <ul class="admin-nav">
            <?php
                foreach($adminNav as $key => $value){
                    if(
                        (!isset( $_SESSION['admin'] ) && $key !== '/admin/login') ||
                        (isset( $_SESSION['admin'] ) && $key === '/admin/login')

                    ){
                        continue;
                    }

                    $isActive = $_SERVER['REQUEST_URI'] === $key ? 'aria-current="page"' : '' ;

                    echo  '<li class="nav_item" '.$isActive.'><a href="'.$key.'">'.$value.'</a></li>';
                }
            ?>
        </ul>
    </nav>