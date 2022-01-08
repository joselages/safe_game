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

        <?php 
            $userName = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $repPassword = $_POST['repeat_password'] ?? '';
        ?>

        <form class="make-form" action="/user/create" method="post">
            <label>
                Name
                <input type="text" name="username" minlength="2" maxlength="15" value="<?php echo $userName ?>" required>
            </label>
            <label>
                Email
                <input type="email" name="email" value="<?php echo $email ?>" required>
            </label>
            <label>
                Password
                <input type="password" name="password" minlength="8" maxlength="1000" value="<?php echo $password ?>" required>
            </label>
            <label>
                Repeat password
                <input type="password" name="repeat_password" minlength="8" maxlength="1000" value="<?php echo $repPassword ?>" required>
            </label>

            <div class="captcha_container">
                <img class="sign_up-captcha" src="/captcha/captcha.php"> 
                <label>
                     What's on the captcha?
                    <input type="text" name="captcha" minlength="6" maxlength="6" required>
                </label>
            </div>

            <button type="submit" name="submit">Sign up</button>
        </form>
        </div>
    </main>

</body>

</html>