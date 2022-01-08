
<?php require('templates/head.php');?>

<body>
    <?php require('templates/nav.php'); ?>

    <main class="safe safe-form">
        <h1>Safe Change Password</h1>
        <div class="-door">

        <?php
        
        if(isset($edit['message'])){
            $feedback = $edit['isEdited'] ? '-positive' : '-negative';
            echo '<p role="alert" class="form-alert '.$feedback.'">'.$edit['message'].'</p>';
        }
        ?>

        <form class="make-form -big-inputs" action="/user/edit" method="post">
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