<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php');?>

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