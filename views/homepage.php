<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php'); ?>

<body>
<?php require('templates/nav.php'); ?>


    <main class="safe home-page">
        <h1>Welcome to the Safe Game!</h1>
        <?php if ($is_logged) { ?>
            <p><a href="/safe/create">Create a safe</a> or try to crack other users' safes:</p>
        <?php } else { ?>
            <p><a href="/user/create">Sign in</a>, <a href="login">log in</a>, <a href="/safe/create">create a safe</a>, or just try to crack one of our users' safe:</p>
        <?php } ?>
        <ul class="safe-list">
            <?php
            foreach ($safes as $safe) {
                $crackedIcon = empty($safe['was_cracked']) ? '🔒' : '🔓' ;
                $crackedTitle =  empty($safe['was_cracked']) ? 'This safe is still uncracked' : 'This safe was cracked' ;
                                
                $creatorName = empty($safe['user_id']) ? '<span class="item-message">' . $safe['creator_name'] . '</span>' : '<a href="'.ROOT.'/user/'.$safe['user_id'].'" class="item-message">' . $safe['creator_name'] . '</a>' ;

                echo '
                    <li class="list-item">
                        <span class="item-state" title="'.$crackedTitle.'">'.$crackedIcon.'</span>
                        <div class="item-info">
                            <p>' . $host . '/safe/' . $safe['safe_id'] . '</p>
                            <div class="item-line">
                                <a href="' . ROOT . 'safe/' . $safe['safe_id'] . '" class="item-play">Play</a>
                                <div>
                                    <span>by:</span>'. $creatorName .'</div>
                            </div>
                        </div>
                    </li>
                    ';
            }
            ?>
        </ul>
    </main>
</body>

</html>