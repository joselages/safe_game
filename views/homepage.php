<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php');?>

<body>
    <main class="safe home-page">
        <h1>Welcome to the Safe Game!</h1>
        <p><a href="/signup">Sign in</a>, <a href="login">log in</a>, <a href="create">create a safe</a>,  or just try to crack one of our users' safe:</p>

        <ul class="safe-list">
            <li class="list-item">
                <span class="item-state" title="This safe is still uncracked">🔒</span>
                <div class="item-info">
                    <p title="Click to copy link">🔗 Link</p>
                    <div class="item-line">
                        <a href="/safe" class="item-play">Play</a>
                        <div>
                            <span>by:</span>
                            <span class="item-message">Nome</span>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </main>
</body>

</html>