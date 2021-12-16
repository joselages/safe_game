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
  radio.addEventListener("click", function () {
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
  radio.addEventListener("click", function () {
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

for (copyBtn of copyLinkBtns) {
  copyBtn.addEventListener("click", (e) => {
    const linkToCopy = e.target.previousElementSibling.innerText;

    navigator.clipboard.writeText(linkToCopy);
  });
}

makeSafeForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  makeSafeForm.elements['submit'].disabled = true;

  const formData = new FormData();

  //console.log(...formData)
   const formInputs = {};
//   let sendEmail = false;
  //console.log(makeSafeForm.elements)

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
    //console.log(fileInput.files[0]);
  }

  if (
    formInputs.hasOwnProperty("code_1") &&
    formInputs.hasOwnProperty("code_2") &&
    formInputs.hasOwnProperty("code_3")
  ) {
    console.log("tem o codigo");
  } else {
    if (formInputs["random"] === "no") {
      console.log("ERRO");
    } else {
      config.randomSafeCode();

      const randomCode = config.safeCode;

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

  const response =  await request.json();

  if(
    request.status !== 201 ||
    response["safeCreated"] === false
  ){
    makeSafeForm.elements['submit'].disabled = false;
    makeSafeForm.insertAdjacentHTML('afterbegin', errorMessage(response["message"]))
  } else {
    showCreatedScreen(response);
  }


});

function errorMessage(err){
  if(err === undefined) err = 'Something went wrong, please try again later'

  return `<p role="alert" class="form-alert -negative">${err}</p>`;
}

function showCreatedScreen(data){

  htmlToInject = `
          <h2>Your safe was created!</h2>

          <p>Here is your safe's link:</p>
          <div class="safe-link">
              <span class="link">www.site.com/${data["safe_id"]}</span>
              <button class="copy-link js-copyLink" title="Copy to clipboard">📋</button>
          </div>

          <p>Here is your safe's cracking code:</p>
          <div class="safe-code">`;   
          data["code"].forEach((code)=>{
            htmlToInject +=`<span>${code}</span>`;
          });

htmlToInject +=`
          </div>
          <p>This is a ${data["private"]  ? 'private' :'public' } safe.<br>
          ${data["private"]  ? 'Only users with the link can access it.' :'Everyone on this platform can try to crack it.' }
          </p>
  `;
  safeSuccessScreen.innerHTML = htmlToInject;
  makeSafeForm.classList.add('-hidden');
  safeSuccessScreen.classList.remove('-hidden');

}

const inputs = document.getElementsByClassName("js-input");
const btn = document.getElementById("openBtn");
const safeDoor = document.querySelector(".js-safeDoor");

const config = {
  padlockValue: 0,
  spinCount: 0,
  snapInterval: 360 / 41,
  safeCode: [],
  userInput: [],
  correctAmount: 0,
  loopCalc(rotationValue) {
    this.spinCount = Math.floor(rotationValue / 360);

    this.padlockValue = Math.ceil(
      41 -
        (rotationValue / this.snapInterval -
          this.spinCount * (360 / this.snapInterval))
    );

    if (this.padlockValue === 41) this.padlockValue = 0;
  },
  randomSafeCode() {
    this.safeCode = [];

    for (let i = 0; i < Infinity; i++) {
      let rand = Math.floor(Math.random() * 41);

      if (this.safeCode.includes(rand)) continue;

      this.safeCode.push(rand);

      if (this.safeCode.length === 3) break;
    }
  },
  checkCode() {
    let correctDigits = 0;

    for (let i = 0; i < this.safeCode.length; i++) {
      if (this.userInput[i] === this.safeCode[i]) {
        correctDigits += 1;
      } else {
        break;
      }
    }

    return correctDigits === this.safeCode.length;
  },
  audioFeedback() {
    let correctNumberAtCurrentPosition = this.safeCode[this.correctAmount];
    const currentNumber = this.padlockValue;

    console.log("if1: ", correctNumberAtCurrentPosition === currentNumber);
    console.log(
      "if2: ",
      this.correctAmount === this.userInput.length ||
        this.userInput.length === 3
    );
    console.log("amount: ", this.correctAmount);

    if (
      correctNumberAtCurrentPosition === currentNumber &&
      (this.correctAmount === this.userInput.length ||
        this.userInput.length === 3)
    ) {
      console.log("certo");
    } else {
      console.log("errado");
    }
  },
};

//config.randomSafeCode();

const padlock = Draggable.create("#circle", {
  type: "rotation",
  cursor: "grab",
  activeCursor: "grabbing",
  liveSnap: function (endValue) {
    let snapToValue =
      Math.round(endValue / config.snapInterval) * config.snapInterval;

    config.loopCalc(snapToValue);
    return snapToValue;
  },
});

padlock[0].addEventListener("release", function () {
  if (config.userInput.length > 2) {
    config.correctAmount = 0;

    config.userInput = []; //reset
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].value = "";
    }
  }

  config.userInput.push(config.padlockValue);

  if (
    config.userInput[config.userInput.length - 1] ===
    config.safeCode[config.userInput.length - 1]
  ) {
    config.correctAmount++;
  }

  inputs[config.userInput.length - 1].value =
    config.userInput[config.userInput.length - 1];

  //config.sendUserInput();

  if (config.userInput.length === config.safeCode.length) {
    btn.classList.remove("-dimmed");
  } else {
    btn.classList.add("-dimmed");
  }
});

padlock[0].addEventListener("drag", () => {
  config.audioFeedback();
});

btn.addEventListener("click", () => {
  if (config.checkCode()) {
    safeDoor.classList.add("-open");
  } else {
    console.log("errado");
  }
});
