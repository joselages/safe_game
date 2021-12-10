<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Game</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔓</text></svg>">
    <link href="./styles/reset.css" rel="stylesheet">
    <link href="./styles/safe.css" rel="stylesheet">
</head>

<body>
    <main class="safe home-page">
        <h1>Welcome to the Safe Game!</h1>
        <p><a href="./signup.php">Sign in</a>, <a href="./login.php">log in</a>, <a href="./form.php">create a safe</a>,  or just try to crack one of our users' safe:</p>

        <ul class="safe-list">
            <li class="list-item">
                <span class="item-state" title="This safe is still uncracked">🔒</span>
                <div class="item-info">
                    <p title="Click to copy link">🔗 Link</p>
                    <div class="item-line">
                        <a href="./safe_game.php" class="item-play">Play</a>
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