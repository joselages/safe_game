<?php require('templates/head.php');?>

<body>
    <?php require('templates/nav.php'); ?>

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