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

                <form class="make-form -big-inputs js-editForm" action="/safe/edit/<?php echo $result['safe']['safe_id'] ?>" method="post" enctype="multipart/form-data">
                    <input class="js-hiddenMessage" type="hidden" name="message">
                    <input class="js-hiddenTextMessage" type="hidden" name="message_text">
                    <label>
                        Message:
                    </label>
                    <div id="editor" rows="3" name="message_fake" spellcheck="false" minlength="8" maxlength="140" required></div>

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

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
  var quill = new Quill('#editor', {
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline', 'link']
        ]
      },
      placeholder: '',
      theme: 'snow'  // or 'bubble'
    });


    const hiddenMessage = document.querySelector('.js-hiddenMessage');
    const hiddenTextMessage = document.querySelector('.js-hiddenTextMessage');


    quill.on('editor-change', () => {

        hiddenMessage.value = JSON.stringify(quill.getContents());
        hiddenTextMessage.value = quill.getText();;
    })


    const message = <?php echo $result['safe']['message']; ?>;

    quill.setContents(JSON.parse(message));
</script>
</body>

</html>