<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php');?>

<body>
    <main class="safe">
        <section class="verification_email safe_door">
            <?php
            $feedbackIcon = $result['isVerified'] ? 'YEAH! 🤘' : 'Oops... 👎';

            ?>
            <span><?php echo $feedbackIcon ?></span>
            <h1><?php echo $result['message'] ?></h1>

            <?php if( $result['isVerified']){ ?>
                <p>This means you can <a href="/login">login</a> now...</p>
            <?php } ?>
        </section>
    </main>

</body>

</html>