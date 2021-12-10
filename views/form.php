<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Game</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔓</text></svg>">
    <link href="./styles/reset.css" rel="stylesheet">
    <link href="./styles/safe.css" rel="stylesheet">
</head>

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
                        <input type="email" name="to">
                    </label>
                    <label>
                        From:
                        <input type="text" name="from" minlength="2" maxlength="15">
                    </label>

                    <label>
                        Message (login to send images):
                        <textarea rows="3" name="message" spellcheck="false" minlength="8" maxlength="140"></textarea>
                    </label>
                    <label class="file-label" title="Add an image">
                        📷
                        <span class="js-fileLabelName">Add an image</span>
                        <input class="js-fileInput" type="file" name="picture" accept="image/png,image/jpeg">
                    </label>
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
                            <input type="number" name="code1" pattern="^[0-9]{1,2}$" min="0" max="40">
                            -
                            <input type="number" name="code2" pattern="^[0-9]{1,2}$" min="0" max="40">
                            -
                            <input type="number" name="code3" pattern="^[0-9]{1,2}$" min="0" max="40">
                        </div>
                    </div>
                    <label class="checkbox-label">
                        <input type="checkbox" name="private">
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
        const randomRadios = document.querySelectorAll('.js-randomRadioBtn');
        const sendByRadios = document.querySelectorAll('.js-sendByRadioBtn');
        const codeInputs = document.querySelector('.js-safeCodeInputs');
        const sendToEmailInput = document.querySelector('.js-sendToEmailInput');
        const fileInput = document.querySelector('.js-fileInput');
        const fileLabelName = document.querySelector('.js-fileLabelName');
        const copyLinkBtns = document.querySelectorAll('.js-copyLink');
        const makeSafeForm = document.querySelector('.js-makeSafeForm');

        for (radio of randomRadios) {
            radio.addEventListener('click', function() {
                if (this.value === "no") {
                    codeInputs.classList.remove('-hidden');
                } else {
                    codeInputs.classList.add('-hidden');
                }
            })
        }

        for (radio of sendByRadios) {
            radio.addEventListener('click', function() {
                if (this.value === "mail") {
                    sendToEmailInput.classList.remove('-hidden');
                } else {
                    sendToEmailInput.classList.add('-hidden');
                }
            })
        }

        fileInput.addEventListener('change', (e) => {
            let newLabelName = e.target.value.split('\\');
            newLabelName = newLabelName[newLabelName.length - 1];

            if (newLabelName === '') {
                newLabelName = 'Add an image';
            }

            fileLabelName.textContent = newLabelName;
            fileLabelName.parentNode.title = newLabelName;
        })


        for (copyBtn of copyLinkBtns) {
            copyBtn.addEventListener('click', (e) => {
                const linkToCopy = e.target.previousElementSibling.innerText;

                navigator.clipboard.writeText(linkToCopy);
            });
        }

        makeSafeForm.addEventListener('submit', (e) => {
            e.preventDefault();

            e.target.classList.add('-hidden');
            document.querySelector('.js-formResult').classList.remove('-hidden')
        })
    </script>
</body>

</html>