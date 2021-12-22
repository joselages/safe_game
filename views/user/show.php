<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php'); ?>

<body>

<?php require('templates/nav.php'); ?>

    <main class="safe safe-form">
        <?php if( $result['status']){ ?>
        <nav class="profile-nav">
            <button class="-active js-profileNav" data-link="profile"><?php echo $username;?> profile</button>
            <button class="js-profileNav" data-link="list"><?php echo $username;?> safes</button>
        </nav>
        <div class="profile-container">

            <section class="user-profile js-profileContent" data-link="profile">
                <?php
                if (
                    isset($_SESSION['user_id']) &&
                    $result['user']['user_id'] === $_SESSION['user_id']
                ) {
                ?>
                    <a href="/user/edit" class="profile-edit">✏️ Change password</a>

                <?php } ?>
                <h2 class="profile-name"><?php echo $result['user']['username']; ?></h2>
                <dl class="profile-info">
                    <div>
                        <dt>Safe created</dt>
                        <dd class="js-safeCount"><?php echo $result['user']['safeCount']; ?></dd>
                    </div>
                    <div>
                        <dt>Safes created cracked amount</dt>
                        <dd><?php echo $result['user']['createdCracked']; ?></dd>
                    </div>
                    <div>
                        <dt>Safes cracked</dt>
                        <dd><?php echo $result['user']['crackedCount']; ?> </dd>
                    </div>
                    <div>
                        <dt>Total cracking time</dt>
                        <dd><?php echo !empty($result['user']['crackingTime']) ? $result['user']['crackingTime'] : 0 ; ?>s</dd>
                    </div>
                    <div>
                        <dt>User since</dt>
                        <dd><?php echo date('d/m/Y', strtotime($result['user']['created_at'])); ?></dd>
                    </div>
                </dl>
            </section>
            <ul class="safe-list js-profileContent -hidden" data-link="list">
                <?php if(
                    !empty($safes)
                ){
                    foreach($safes as $safe){
                        if(
                            $safe['is_private'] === '1' && 
                            ( $is_logged && $result['user']['user_id'] !== $_SESSION['user_id'] )
                        ) {
                            continue;
                        }

                        $crackedIcon = empty($safe['was_cracked']) ? '🔒' : '🔓' ;
                        $crackedTitle =  empty($safe['was_cracked']) ? 'This safe is still uncracked' : 'This safe was cracked' ;
                    ?>
                        <li class="list-item" data-safe="<?php echo $safe['safe_id'] ?>">
                            <?php echo '<span class="item-state" title="'.$crackedTitle.'">'.$crackedIcon.'</span>'; ?>
                            <div class="item-info">
                                <div class="safe-link">
                                    <span class="link"><?php echo $host .'/safe/'.$safe['safe_id'] ?></span>
                                    <button class="copy-link js-copyLink" title="Copy to clipboard">📋</button>
                                </div>
                                
                                <?php if(
                                    $is_logged &&
                                    $_SESSION['user_id'] === $id
                                ){ ?>
                                    <div class="item-code">
                                        <?php foreach(explode('/', $safe['code']) as $code){
                                            echo '<span>'.$code.'</span>';
                                        } ?>

                                    </div>
                                    <p class="item-message">✉️ <?php echo substr($safe['message'], 0, 15) ?>...</p>
                                <?php 
                                     }
                                ?>
                            </div>
                            <?php if(
                                    $is_logged &&
                                    $_SESSION['user_id'] === $id
                            ){ ?>
                                <div class="item-btns">
                                    <button data-safe="<?php echo $safe['safe_id'] ?>" title="Edit">✏️</button>
                                    <button data-safe="<?php echo $safe['safe_id'] ?>" class="js-deleteSafe" title="Delete">🗑️</button>
                                </div>
                            <?php 
                                } else {
                                    echo '<a href="/safe/' . $safe['safe_id'] . '" class="item-play">Play</a>';
                                }
                            ?>
                        </li>
                <?php 
                    }
                } 
                ?>
            </ul>
        </div>
        <?php } else { ?>
            <p role="alert" class="form-alert -negative"><?php echo $result['message'] ?></p>
        <?php } ?>
    </main>

    <aside class="safe-modal -hidden js-deleteModal">
        <div>
            Are you sure you want to delete this safe?
            <div>
                <button class="js-closeDeleteModal modal-close">&times;</button>
                <button class="modal-delete js-confirmDeletion">Delete this safe</button>
            </div>
        </div>
    </aside>

    <aside class="safe-modal -hidden js-deleteFeedbackModal">
        <div>
            <p></p>
            <button class="js-closeFeedbackModal modal-close">&times;</button>
        </div>
    </aside>


    <script>
        const profileNav = document.querySelectorAll('.js-profileNav');
        const profileContent = document.querySelectorAll('.js-profileContent');

        function toggleContent(evt) {
            if (evt.target.classList.contains('-active')) return;

            for (btn of profileNav) {
                if (btn.classList.contains('-active')) {
                    btn.classList.remove('-active')
                }
            }

            evt.target.classList.add('-active');

            const linkTo = evt.target.dataset.link;

            for (content of profileContent) {
                if (content.dataset.link === linkTo && content.classList.contains('-hidden')) {
                    content.classList.remove('-hidden');
                } else {
                    content.classList.add('-hidden');
                }
            }
        }

        for (nav of profileNav) {
            nav.addEventListener('click', (evt) => toggleContent(evt));
        }

        const deleteSafeBtns = document.querySelectorAll('.js-deleteSafe');
        const deleteModal = document.querySelector('.js-deleteModal');
        const closeDeleteModal = document.querySelector('.js-closeDeleteModal');
        const closeFeedbackModal = document.querySelector('.js-closeFeedbackModal');
        const confirmDeletionBtn = document.querySelector('.js-confirmDeletion');
        const deleteFeedbackModal = document.querySelector('.js-deleteFeedbackModal');
        const safeCountEl = document.querySelector('.js-safeCount');

        for (deleteBtn of deleteSafeBtns) {
            deleteBtn.addEventListener('click', (e) => {
                const safeId = e.target.dataset.safe;

                confirmDeletionBtn.dataset.safe = safeId;
                deleteModal.classList.remove('-hidden')
            });
        }

        confirmDeletionBtn.addEventListener('click', async (evt)=>{
            const safeId = evt.target.dataset.safe;

            const request = await fetch('/safe/'+safeId, {
                method:'DELETE'
            });

            const response = await request.json();

            if(
                request.status === 202 ||
                response['isDeleted']
            ){
                
                document.querySelector(`.list-item[data-safe='${response['safe_id']}']`).remove();
                const safeCount = Number(safeCountEl.innerHTML);
                safeCountEl.innerHTML = safeCount - 1;

            }

            deleteFeedbackModal.firstElementChild.firstElementChild.textContent = response['message'];
            deleteModal.classList.add('-hidden');
            deleteFeedbackModal.classList.remove('-hidden');
        });

        closeDeleteModal.addEventListener('click', (e) => {
            deleteModal.dataset.safe = "";
            deleteModal.classList.add('-hidden');
        });

        closeFeedbackModal.addEventListener('click', (e) => {
            confirmDeletionBtn.dataset.safe = "";
            deleteModal.dataset.safe = "";

            deleteFeedbackModal.classList.add('-hidden');
        });

        const copyLinkBtns = document.querySelectorAll('.js-copyLink');

        for (copyBtn of copyLinkBtns) {
            copyBtn.addEventListener('click', (e) => {
                const linkToCopy = e.target.previousElementSibling.innerText;

                navigator.clipboard.writeText(linkToCopy);
            });
        }


    </script>
    
</body>


</html>