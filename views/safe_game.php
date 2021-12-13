<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php');?>

<body>
    <main class="safe">
        <section class="safe_door js-safeDoor">
            <div class="safe_door-front">
                <div class="safe_digits">
                    <input class="js-input border-black border-2 p-4 w-16" type="text" readonly>
                    <input class="js-input border-black border-2 p-4 w-16" type="text" readonly>
                    <input class="js-input border-black border-2 p-4 w-16" type="text" readonly>
                </div>

                <div class="safe_wheel-container">
                    <img class="safe_wheel" id="circle" src="<?php ROOT ?>/images/11safedial.png">
                    <button class="safe_open-btn -dimmed" id="openBtn">OPEN</button>
                </div>

            </div>

            <div class="safe_door-back"></div>
        </section>

        <section class="safe_inside">
            <div class="inside-txt">
                Nothing here for you, you cheater...
            </div>
        </section>

        <span class="safe_creator">Safe created by: <a href="/user">Name</a></span>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/Draggable.min.js"></script>

    <script>
        const inputs = document.getElementsByClassName('js-input');
        const btn = document.getElementById('openBtn');
        const safeDoor = document.querySelector('.js-safeDoor');

        const config = {
            padlockValue: 0,
            spinCount: 0,
            snapInterval: 360 / 41,
            safeCode: [1, 2, 3],
            userInput: [],
            correctAmount: 0,
            loopCalc(rotationValue) {
                this.spinCount = Math.floor(rotationValue / 360);

                this.padlockValue =
                    Math.ceil(41 -
                        (rotationValue / this.snapInterval -
                            this.spinCount * (360 / this.snapInterval)));

                if (this.padlockValue === 41) this.padlockValue = 0;
            },
            randomSafeCode() {
                for (let i = 0; i < Infinity; i++) {
                    let rand = Math.floor(Math.random() * 41);

                    if (this.safeCode.includes(rand)) continue;

                    this.safeCode.push(rand);

                    if (this.safeCode.length === 3) break;
                }

                console.log(this.safeCode)
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

                console.log('if1: ', correctNumberAtCurrentPosition === currentNumber)
                console.log('if2: ', (this.correctAmount === this.userInput.length || this.userInput.length === 3))
                console.log('amount: ', this.correctAmount)

                if (
                    correctNumberAtCurrentPosition === currentNumber &&
                    (this.correctAmount === this.userInput.length || this.userInput.length === 3)
                ) {
                    console.log('certo')
                } else {
                    console.log('errado')
                }
            }
        };

        //config.randomSafeCode();

        const padlock = Draggable.create("#circle", {
            type: "rotation",
            cursor: "grab",
            activeCursor: "grabbing",
            liveSnap: function(endValue) {
                let snapToValue =
                    Math.round(endValue / config.snapInterval) * config.snapInterval;

                config.loopCalc(snapToValue);
                return snapToValue;
            },
        });

        padlock[0].addEventListener("release", function() {


            if (config.userInput.length > 2) {
                config.correctAmount = 0;

                config.userInput = []; //reset
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].value = "";
                }
            }

            config.userInput.push(config.padlockValue);


            if (config.userInput[config.userInput.length - 1] === config.safeCode[config.userInput.length - 1]) {
                config.correctAmount++;
            }

            inputs[config.userInput.length - 1].value = config.userInput[config.userInput.length - 1];

            //config.sendUserInput();

            if (config.userInput.length === config.safeCode.length) {
                btn.classList.remove('-dimmed');
            } else {
                btn.classList.add('-dimmed');
            }
        });

        padlock[0].addEventListener("drag", () => {
            config.audioFeedback();
        });


        btn.addEventListener('click', () => {
            if (config.checkCode()) {
                safeDoor.classList.add('-open');
            } else {
                console.log('errado')
            }
        });
    </script>
</body>

</html>