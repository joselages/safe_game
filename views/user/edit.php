<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php');?>

<body>
    <main class="safe safe-form">
        <h1>Safe Edit Profile</h1>
        <div class="-door">

        <?php
        
        if(isset($result['message'])){
            $feedback = $result['isEdited'] ? '-positive' : '-negative';
            echo '<p role="alert" class="form-alert '.$feedback.'">'.$result['message'].'</p>';
        }
        ?>

        <form class="make-form -big-inputs" action="/user/edit" method="post">
            <label>
                Name
                <input type="text" name="username" minlength="2" maxlength="15" value="<?php echo $user['username'] ?>" required>
            </label>
            <label>
                Password
                <input type="password" name="password" minlength="8" maxlength="1000" required>
            </label>
            <label>
                Confirm Password
                <input type="password" name="repeat_password" minlength="8" maxlength="1000" required>
            </label>

            <button type="submit" name="submit">Edit</button>
        </form>
        </div>
    </main>

</body>

</html>