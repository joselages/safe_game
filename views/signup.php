
<?php require('templates/head.php');?>

<body>
  <?php require('templates/nav.php'); ?>

    <main class="safe safe-form">
        <h1>Safe Sign Up</h1>
        <div class="-door">


        <form class="make-form -big-inputs" action="" method="post">
            <label>
                Name
                <input type="text" name="name" minlength="2" maxlength="15" required>
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