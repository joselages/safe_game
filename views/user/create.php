<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php');?>

<body>
    <?php require('templates/nav.php'); ?>

    <main class="safe safe-form">
        <h1>Safe Sign Up</h1>
        <div class="-door">

        <?php
        
        if(isset($result['message'])){
            $feedback = $result['isStored'] ? '-positive' : '-negative';

            echo '<p role="alert" class="form-alert '.$feedback.'">'.$result['message'].'</p>';
        }
        ?>

        <form class="make-form -big-inputs" action="/user/create" method="post">
            <label>
                Name
                <input type="text" name="username" minlength="2" maxlength="15" required>
            </label>
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Password
                <input type="password" name="password" minlength="8" maxlength="1000" required>
            </label>
            <label>
                Repeat password
                <input type="password" name="repeat_password" minlength="8" maxlength="1000" required>
            </label>

            <button type="submit" name="submit">Sign up</button>
        </form>
        </div>
    </main>

</body>

</html>