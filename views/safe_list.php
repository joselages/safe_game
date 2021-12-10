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
        <nav class="profile-nav">
            <button class="-active js-profileNav" data-link="profile">My profile</button>
            <button class="js-profileNav" data-link="list">My safes</button>
        </nav>
        <div class="profile-container">

            <section class="user-profile js-profileContent" data-link="profile">
                <button class="profile-edit">✏️ Edit profile</button>
                <h2 class="profile-name">Name</h2>
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
                        <dd>11-06-1992</dd>
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