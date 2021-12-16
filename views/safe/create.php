<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php'); ?>

<body>
    <main class="safe safe-form">
        <h1>Safe Generator</h1>
        <div class="form-container ">
            <form class="make-form js-makeSafeForm" action="" method="post">

                <div class="form-overflow">
                    <div class="form-radios js-sendByContainer">
                        <label class="radio-label">
                            Send by mail
                            <input class="js-sendByRadioBtn" type="radio" name="send-by" value="mail" checked>
                        </label>
                        <label class="radio-label">
                            Get a link
                            <input class="js-sendByRadioBtn" type="radio" name="send-by" value="link">
                        </label>
                    </div>
                    <label class="js-sendToEmailInput">
                        To:
                        <input type="email" name="email-to" required>
                    </label>
                    <?php if (!$is_logged) { ?>
                        <label>
                            From:
                            <input type="text" name="creator_name" minlength="2" maxlength="15" required>
                        </label>
                    <?php } ?>
                    <label>
                        Message<?php if (!$is_logged) { ?> (login to send images)<?php } ?>:
                        <textarea rows="3" name="message" spellcheck="false" minlength="8" maxlength="140" required></textarea>
                    </label>
                    <?php if ($is_logged) { ?>
                        <label class="file-label" title="Add an image">
                            📷
                            <span class="js-fileLabelName">Add an image</span>
                            <input class="js-fileInput" type="file" name="picture" accept="image/png,image/jpeg">
                        </label>
                    <?php } ?>

                    Do you want a random safe code?
                    <div class="form-radios">
                        <label>
                            Yes
                            <input class="js-randomRadioBtn" type="radio" name="random" value="yes" checked>
                        </label>
                        <label>
                            No
                            <input class="js-randomRadioBtn" type="radio" name="random" value="no">
                        </label>
                    </div>
                    <div class="js-safeCodeInputs -hidden">

                        Safe code:
                        <div class="form-code">
                            <input type="number" name="code_1" pattern="^[0-9]{1,2}$" min="0" max="40">
                            -
                            <input type="number" name="code_2" pattern="^[0-9]{1,2}$" min="0" max="40">
                            -
                            <input type="number" name="code_3" pattern="^[0-9]{1,2}$" min="0" max="40">
                        </div>
                    </div>
                    <label class="checkbox-label">
                        <input type="checkbox" value="private" name="private">
                        Check to make this safe private
                    </label>
                </div>

                <button class="-on-bottom" type="submit" name="submit">Submit</button>
            </form>

            <section class="form-result js-formResult -hidden">
                <h2>Your safe was created!</h2>

                <p>Here is your safe's link:</p>
                <div class="safe-link">
                    <span class="link">Link</span>
                    <button class="copy-link js-copyLink" title="Copy to clipboard">📋</button>
                </div>

                <p>Here is your safe's cracking code:</p>
                <div class="safe-code">
                    <span>01</span>
                    <span>02</span>
                    <span>03</span>
                </div>

                <p>This is a public/private safe.<br>Everyone on this platform can try to crack it.<br>Only users with the link can access it.</p>

            </section>
        </div>
    </main>

    <script>

    </script>
</body>

</html>