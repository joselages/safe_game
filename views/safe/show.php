<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php');?>

<body>
    <main class="safe" >
        <?php if(empty($_SESSION["safe"])){ ?>
        
            <p role="alert" class="form-alert -negative"><?php echo $result['message'] ?></p>
        
        <?php } else { ?>
            <section class="safe_door js-safeDoor">
                <div class="safe_door-front">
                    <div class="safe_digits">
                        <input class="js-input border-black border-2 p-4 w-16" type="text" readonly>
                        <input class="js-input border-black border-2 p-4 w-16" type="text" readonly>
                        <input class="js-input border-black border-2 p-4 w-16" type="text" readonly>
                    </div>

                    <div class="safe_wheel-container">
                        <img class="safe_wheel" id="circle" src="<?php ROOT ?>/images/11safedial.png">
                        <button class="safe_open-btn -dimmed" id="openBtn" data-id="<?php echo $id ?>">OPEN</button>
                    </div>
                </div>

                <div class="safe_door-back"></div>
            </section>

            <section class="safe_inside">
                <div class="inside-txt js-safeInside">
                    Nothing here for you, you cheater...
                </div>
            </section>

            <?php 
                $userId = $_SESSION["safe"]['user_id'];
                $userName = $_SESSION["safe"]['safe_creator'];
                $userLink = 'user/' . $userId;

                echo '<span class="safe_creator">Safe created by: '.(!empty($userId)  ? '<a href="'.ROOT.'/' . $userLink .'">'.$userName.'</a>' : '<span>'.$userName.'</span>').'</span>';
            ?>
            
            <?php } ?>

    </main>

    <?php if(!empty($_SESSION["safe"])){ ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/Draggable.min.js"></script>
        <script>
            const inputs = document.getElementsByClassName('js-input');
            const btn = document.getElementById('openBtn');
            const safeDoor = document.querySelector('.js-safeDoor');
            const safeInside = document.querySelector('.js-safeInside');
            const config = {
                padlockValue: 0,
                spinCount: 0,
                snapInterval: 360 / 41,
                safeCode: [<?php 
                    foreach($_SESSION["safe"]["code"] as $code) {
                        echo $code . ",";
                    }
                ?>],
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




            btn.addEventListener('click', async (event) => {

                if(
                    config.userInput.length !== 3
                ){
                    return;
                }

                const toSend = new FormData();

                toSend.set('request', 'checkCode');
                toSend.set('safe_id', event.target.dataset.id);

                config.userInput.forEach((code, idx)=>{
                    toSend.set('code_'+(idx+1), code);
                });

                const request = await fetch("/safe/show", {
                    method: "POST",
                    body: toSend
                });

                const response = await request.json();

                console.log(response)

                if(
                    request.status !== 200 ||
                    response["status"] === false
                ){
                    //tocar musica de erro
                    return;
                }

                messageToInject =`
                    ${ response["content"]['image_path'] ? '<img src="<?php echo ROOT ?>/uploads/20211215224508_312bd514.jpg">' : '' } 
                    ${ response["content"]['message'] }
                `;

                safeInside.innerHTML = messageToInject;
                safeDoor.classList.add('-open');
            });
        </script>
    <?php } ?>
</body>

</html>