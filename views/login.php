<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Game</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔓</text></svg>">
    <link href="./styles/reset.css" rel="stylesheet">
    <link href="./styles/safe.css" rel="stylesheet">
</head>

<body>
    <main class="safe safe-form">
        <h1>Safe Login</h1>
        <div class="-door">
        <form class="make-form -big-inputs" action="" method="post">
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Password
                <input type="password" name="password" minlength="8" maxlength="1000" required>
            </label>

            <butto type="submit" name="submit">Login</button>
        </form>
        </div>

    </main>

</body>

</html>