<?php require('templates/head.php');?>

<body>
<?php require('templates/nav.php'); ?>


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

    <audio id="wrong-click">
        <source src="/audio/click.mp3" type="audio/mpeg">
    </audio> 
    <audio id="right-click">
        <source src="/audio/click-win.mp3" type="audio/mpeg">
    </audio> 

    <?php if(!empty($_SESSION["safe"])){ ?>
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
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
                        Math.round(41 -
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

                    if (
                        correctNumberAtCurrentPosition === currentNumber &&
                        (this.correctAmount === this.userInput.length || this.userInput.length === 3)
                    ) {
                        document.getElementById('wrong-click').pause();
                        document.getElementById('wrong-click').currentTime = 0;
                        document.getElementById('right-click').pause();
                        document.getElementById('right-click').currentTime = 0;
                        document.getElementById('right-click').play();
                    } else {
                        document.getElementById('right-click').pause();
                        document.getElementById('right-click').currentTime = 0;
                        document.getElementById('wrong-click').pause();
                        document.getElementById('wrong-click').currentTime = 0;
                        document.getElementById('wrong-click').play();
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

                //info para se ganhar
                toSend.set('seconds_to_crack', calculateSeconds());


                const request = await fetch("/safe/show", {
                    method: "POST",
                    body: toSend
                });

                const response = await request.json();

                if(
                    request.status !== 200 ||
                    response["status"] === false
                ){
                    return;
                }

                const htmlMessage = deltaToHtml(  response["content"]['message'] );

                messageToInject =`
                    ${ response["content"]['image_path'] ? `<img src="<?php echo ROOT; ?>/${response["content"]['image_path']}">` : '' } 
                    ${ htmlMessage }
                `;

                safeInside.innerHTML = messageToInject;
                safeDoor.classList.add('-open');
            });

            const startedDate = new Date();
            function calculateSeconds(){
                const now = new Date();

                const msSpentCracking = now - startedDate;

                return Math.round(msSpentCracking / 1000);
            }



            function deltaToHtml(delta){
                delta = JSON.parse(JSON.parse(delta));
 
                const tempEl = document.createElement('div')
                const tempQuill = new Quill(tempEl);
                tempQuill.setContents(delta, 'api');


                return tempEl.querySelector('.ql-editor').innerHTML;
            }
        </script>
    <?php } ?>
</body>

</html>