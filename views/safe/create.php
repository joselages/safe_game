<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php'); ?>

<body>
  <?php require('templates/nav.php'); ?>

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
            </label>
            <div id="editor" rows="3" name="message" spellcheck="false" minlength="8" maxlength="140" required></div>
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

      </section>
    </div>
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

    const randomRadios = document.querySelectorAll(".js-randomRadioBtn");
    const sendByRadios = document.querySelectorAll(".js-sendByRadioBtn");
    const codeInputs = document.querySelector(".js-safeCodeInputs");
    const sendToEmailInput = document.querySelector(".js-sendToEmailInput");
    const fileInput = document.querySelector(".js-fileInput");
    const fileLabelName = document.querySelector(".js-fileLabelName");
    const copyLinkBtns = document.querySelectorAll(".js-copyLink");
    const makeSafeForm = document.querySelector(".js-makeSafeForm");
    const safeSuccessScreen = document.querySelector('.js-formResult');


    for (radio of randomRadios) {
      radio.addEventListener("click", function() {
        let switchToRequired = false;
        if (this.value === "no") {
          codeInputs.classList.remove("-hidden");
          switchToRequired = true;
        } else {
          codeInputs.classList.add("-hidden");
        }

        for (input of codeInputs.children[0].children) {
          input.required = switchToRequired;

          if (switchToRequired === false) {
            input.value = "";
          }
        }
      });
    }

    for (radio of sendByRadios) {
      radio.addEventListener("click", function() {
        if (this.value === "mail") {
          sendToEmailInput.classList.remove("-hidden");
          sendToEmailInput.children["email-to"].required = true;
        } else {
          sendToEmailInput.classList.add("-hidden");
          sendToEmailInput.children["email-to"].required = false;
          sendToEmailInput.children["email-to"].value = "";
        }
      });
    }

    if (fileInput !== null) {
      fileInput.addEventListener("change", (e) => {
        let newLabelName = e.target.value.split("\\");
        newLabelName = newLabelName[newLabelName.length - 1];

        if (newLabelName === "") {
          newLabelName = "Add an image";
        }

        fileLabelName.textContent = newLabelName;
        fileLabelName.parentNode.title = newLabelName;
      });
    }

    const copyLink = (el) => {
      const linkToCopy = el.previousElementSibling.innerText;

      navigator.clipboard.writeText(linkToCopy);
    };

    makeSafeForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      makeSafeForm.elements['submit'].disabled = true;

      const RTEContent = quill.getContents();
      const RTETotalLength = quill.getLength();
      const RTEText = quill.getText();

      if(
        RTEText.length < 8 ||
        RTEText.length > 140
      ){
        showError('Message field has to be between 8 and 140 chars');
        return;
      }

      const formData = new FormData();
      formData.set('message', JSON.stringify(RTEContent));
      formData.set('message_text', RTEText);

      const formInputs = {};

      for (el of makeSafeForm.elements) {
        if (el.type === "checkbox") {
          formData.set(el.name, el.checked);
          formInputs[el.name] = el.checked;
          continue;
        }

        if (el.type === "radio" && el.checked) {
          formData.set(el.name, el.value);
          formInputs[el.name] = el.value;
          continue;
        }

        if (el.required !== true) {
          continue;
        }

        formData.set(el.name, el.value);
        formInputs[el.name] = el.value;
      }

      if (fileInput !== null && fileInput.files[0]) {
        formData.set(fileInput.name, fileInput.files[0], fileInput.value);
      }

      if (
        formInputs.hasOwnProperty("code_1") &&
        formInputs.hasOwnProperty("code_2") &&
        formInputs.hasOwnProperty("code_3")
      ) {
        console.log("tem o codigo");
      } else {
        if (formInputs["random"] === "no") {
          return showError('Wrong input pattern...')
        } else {
          const randomCode = randomSafeCode();
          randomCode.forEach((val, idx) => {
            formData.set("code_" + (idx + 1), val);
          });
        }
      }

      formData.set("request", 'createSafe');


      const request = await fetch("/safe/create", {
        method: "POST",
        body: formData
      });

      const response = await request.json();

      if (
        request.status !== 201 ||
        response["safeCreated"] === false
      ) {
        showError(response["message"])
      } else {
        makeSafeForm.elements['submit'].disabled = false;
        showCreatedScreen(response);
      }
    
    });

    function showError(err) {
      if (err === undefined) err = 'Something went wrong, please try again later'
      
      makeSafeForm.elements['submit'].disabled = false;
      const error = document.querySelector('.form-alert');

      if(error !== null) error.remove();

      makeSafeForm.insertAdjacentHTML('afterbegin', `<p role="alert" class="form-alert -negative">${err}</p>`);
    }

    function showCreatedScreen(data) {

      htmlToInject = `
          <h2>Your safe was created!</h2>

          <p>${data["message"]}</p>

          <p>Here is your safe's link:</p>
          <div class="safe-link">
              <span class="link"><?php echo $host; ?>/safe/${data["safe_id"]}</span>
              <button class="copy-link" title="Copy to clipboard" onclick="copyLink(this)">📋</button>
          </div>

          <p>Here is your safe's cracking code:</p>
          <div class="safe-code">`;
      data["code"].forEach((code) => {
        htmlToInject += `<span>${code}</span>`;
      });

      htmlToInject += `
          </div>
          <p>This is a ${data["private"]  ? 'private' :'public' } safe.<br>
          ${data["private"]  ? 'Only users with the link can access it.' :'Everyone on this platform can try to crack it.' }
          </p>
  `;
      safeSuccessScreen.innerHTML = htmlToInject;
      makeSafeForm.classList.add('-hidden');
      safeSuccessScreen.classList.remove('-hidden');

    }

    function randomSafeCode() {
      let code = [];
      for (let i = 0; i < Infinity; i++) {
        let rand = Math.floor(Math.random() * 41);

        if (code.includes(rand)) continue;

        code.push(rand);

        if (code.length === 3) break;
      }

      return code;
    };
  </script>
</body>

</html>