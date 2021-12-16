<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php'); ?>

<body>
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
                echo '
                    <li class="list-item">
                        <span class="item-state" title="This safe is still uncracked">🔒</span>
                        <div class="item-info">
                            <p title="Click to copy link">🔗 ' . ROOT . 'safe/' . $safe['safe_id'] . '</p>
                            <div class="item-line">
                                <a href="' . ROOT . 'safe/' . $safe['safe_id'] . '" class="item-play">Play</a>
                                <div>
                                    <span>by:</span>
                                    <span class="item-message">' . $safe['creator_name'] . '</span>
                                </div>
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