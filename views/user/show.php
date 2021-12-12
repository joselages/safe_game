<!DOCTYPE html>
<html lang="pt">

<?php require('templates/head.php'); ?>

<body>
    <main class="safe safe-form">
        <nav class="profile-nav">
            <button class="-active js-profileNav" data-link="profile">My profile</button>
            <button class="js-profileNav" data-link="list">My safes</button>
        </nav>
        <div class="profile-container">

            <section class="user-profile js-profileContent" data-link="profile">
                <?php
                if (
                    isset($_SESSION['user_id']) &&
                    $user['user_id'] == $_SESSION['user_id']
                ) {
                ?>
                    <a href="/user/edit" class="profile-edit">✏️ Edit profile</a>

                <?php } ?>
                <h2 class="profile-name"><?php echo $user['username']; ?></h2>
                <dl class="profile-info">
                    <div>
                        <dt>Safes created</dt>
                        <dd>00</dd>
                    </div>
                    <div>
                        <dt>Safes created cracked</dt>
                        <dd>00</dd>
                    </div>
                    <div>
                        <dt>Safes cracked</dt>
                        <dd>00</dd>
                    </div>
                    <div>
                        <dt>Total cracking time</dt>
                        <dd>00m</dd>
                    </div>
                    <div>
                        <dt>User since</dt>
                        <dd><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></dd>
                    </div>
                </dl>
            </section>
            <ul class="safe-list js-profileContent -hidden" data-link="list">
                <li class="list-item">
                    <span class="item-state" title="This safe is still uncracked">🔒</span>
                    <div class="item-info">
                        <div class="safe-link">
                            <span class="link">Link</span>
                            <button class="copy-link js-copyLink" title="Copy to clipboard">📋</button>
                        </div>
                        <div class="item-code">
                            <span>01</span>
                            <span>02</span>
                            <span>03</span>
                        </div>
                        <p class="item-message">✉️ Message....</p>
                    </div>
                    <div class="item-btns">
                        <button data-id="1" title="Edit">✏️</button>
                        <button data-id="1" class="js-deleteSafe" title="Delete">🗑️</button>
                    </div>
                </li>

            </ul>
        </div>

    </main>

    <aside class="safe-modal -hidden js-deleteModal">
        <div>
            Are you sure you want to delete this safe?
            <div>
                <button class="js-closeModal modal-close">&times;</button>
                <button class="modal-delete">Delete this safe</button>
            </div>
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
        const closeModal = document.querySelector('.js-closeModal');

        for (deleteBtn of deleteSafeBtns) {
            deleteBtn.addEventListener('click', (e) => {
                const safeId = e.target.dataset.id;

                deleteModal.dataset.id = safeId;
                deleteModal.classList.remove('-hidden')
            });
        }

        closeModal.addEventListener('click', (e) => {
            deleteModal.dataset.id = "";
            deleteModal.classList.add('-hidden');
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