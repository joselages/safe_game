<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php');?>

<body>
    <main class="safe safe-form">
        <h1>Safe Edit Profile</h1>
        <div class="-door">


        <form class="make-form -big-inputs" action="" method="post">
            <label>
                Name
                <input type="text" name="name" minlength="2" maxlength="15" value="<?php echo $user['username'] ?>" required>
            </label>
            <label>
                Password
                <input type="text" name="password" minlength="8" maxlength="1000" required>
            </label>
            <label>
                Confirm Password
                <input type="text" name="password_confirm" minlength="8" maxlength="1000" required>
            </label>

            <button type="submit" name="submit">Edit</button>
        </form>
        </div>
    </main>

</body>

</html>