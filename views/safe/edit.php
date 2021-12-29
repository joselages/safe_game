<!DOCTYPE html>
<html lang="en">

<?php require('templates/head.php'); ?>

<body>
    <?php require('templates/nav.php'); ?>

    <main class="safe safe-form">

        <?php if($result['isOk']){ ?>
            <h1>Safe <?php echo $result['safe']['safe_id'] ?> Edit</h1>
            <div class="-door">

                <?php

                if (isset($edit['message'])) {
                    $feedback = $edit['status'] ? '-positive' : '-negative';
                    echo '<p role="alert" class="form-alert ' . $feedback . '">' . $edit['message'] . '</p>';
                }
                ?>

                <form class="make-form -big-inputs" action="/safe/edit/<?php echo $result['safe']['safe_id'] ?>" method="post" enctype="multipart/form-data">
                    <label>
                        Message:
                        <textarea rows="3" name="message" spellcheck="false" minlength="8" maxlength="140" required><?php echo $result['safe']['message'] ?></textarea>
                    </label>

                    <?php if (!empty($result['safe']['image_path'])) { ?>
                        <div class="form-image-display">
                            <div class="safe_image">
                                <img  src="/<?php echo $result['safe']['image_path'] ?>">
                            </div>
                            <label class="checkbox-label">
                                <input type="checkbox" value="delete_image" name="delete_image">
                                Delete image
                            </label>
                        </div>
                    <?php } ?>

                        <label class="file-label" title="Add an image">
                            📷
                            <?php if (empty($result['safe']['image_path'])) { ?>
                                <span class="js-fileLabelName">Add an image</span>
                            <?php } else { ?>
                                <span class="js-fileLabelName">Change image</span>
                            <?php } ?>
                            <input class="js-fileInput" type="file" name="picture" accept="image/png,image/jpeg">
                        </label>

                    <div>
                        Safe code:
                        <div class="form-code">
                            <input type="number" name="code_1" pattern="^[0-9]{1,2}$" min="0" max="40" value="<?php echo $result['safe']['code'][0] ?>">
                            -
                            <input type="number" name="code_2" pattern="^[0-9]{1,2}$" min="0" max="40" value="<?php echo $result['safe']['code'][1] ?>">
                            -
                            <input type="number" name="code_3" pattern="^[0-9]{1,2}$" min="0" max="40" value="<?php echo $result['safe']['code'][2] ?>">
                        </div>
                    </div>

                    <label class="checkbox-label">
                        <?php 
                            $checkBoxState = '';
                            
                            if($result['safe']['is_private'] === '1'){
                                $checkBoxState = 'checked';
                            } 
                        ?>
                        <input type="checkbox" value="private" name="private" <?php echo $checkBoxState; ?>>
                        Check to make this safe private
                    </label>

                    <button type="submit" name="submit">Edit</button>
                </form>
            </div>
        <?php } else { ?>
            <p role="alert" class="form-alert -negative"><?php echo $result['message'] ?></p>
        <?php } ?>
    </main>

</body>

</html>