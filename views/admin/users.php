
<?php require('templates/head.php'); ?>

<body>

    <?php require('templates/nav.php'); ?>
    <main class="safe safe-form -admin">
        <h1>Admin Users</h1>

        <div class="profile-container">
            <ul class="safe-list">
                <?php if (
                    !empty($users)
                ) {
                    foreach ($users as $user) {
                ?>
                        <li class="list-item" data-user="<?php echo $user['user_id'] ?>">
                            <span class="item-state">🧍</span>
                            <div class="item-info -user">
                                <span>Username: <p><?php echo $user['username']; ?></p></span>
                                <span>Email: <p><?php echo $user['email']; ?></p></span>
                                <span>User since:<p><?php echo $user['created_at']; ?></p></span>
                                <span>Safes: <p><?php echo $user['safeCount']; ?></p></span>

                                <span>Click to change permissions:</span>
                                <div class="flex space-between">
                                    <span>Verified:<span data-verified="<?php echo $user['is_verified']; ?>" data-id="<?php echo $user['user_id']; ?>" class="user_btn js-verificationUserBtn"><?php echo $user['is_verified'] ? '✅' : '❌'; ?></span></span>
                                    <span>Admin:<span data-admin="<?php echo $user['is_admin']; ?>" data-id="<?php echo $user['user_id']; ?>" class="user_btn js-adminUserBtn"><?php echo $user['is_admin'] ? '✅' : '❌'; ?></span></span>
                                </div>
                            </div>

                            <div class="item-btns">
                                <button data-safe="<?php echo $user['user_id'] ?>" class="item-btn js-deleteSafe" title="Delete">🗑️</button>
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
    </main>

    <aside class="safe-modal -hidden js-deleteModal">
        <div>
            Are you sure you want to delete this user?
            <div>
                <button class="js-closeDeleteModal modal-close">&times;</button>
                <button class="modal-delete js-confirmDeletion">Delete this user</button>
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
        const deleteSafeBtns = document.querySelectorAll('.js-deleteSafe');
        const deleteModal = document.querySelector('.js-deleteModal');
        const closeDeleteModal = document.querySelector('.js-closeDeleteModal');
        const closeFeedbackModal = document.querySelector('.js-closeFeedbackModal');
        const confirmDeletionBtn = document.querySelector('.js-confirmDeletion');
        const deleteFeedbackModal = document.querySelector('.js-deleteFeedbackModal');

        for (deleteBtn of deleteSafeBtns) {
            deleteBtn.addEventListener('click', (e) => {
                const userId = e.target.dataset.safe;

                confirmDeletionBtn.dataset.safe = userId;
                deleteModal.classList.remove('-hidden')
            });
        }

        confirmDeletionBtn.addEventListener('click', async (evt) => {
            const userId = evt.target.dataset.safe;

            const request = await fetch('/admin/users/' + userId, {
                method: 'DELETE'
            });

            const response = await request.json();

            if (
                request.status === 202 ||
                response['isDeleted']
            ) {
                document.querySelector(`.list-item[data-user='${response['user_id']}']`).remove();
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

        const verificationBtn = document.querySelectorAll('.js-verificationUserBtn');

        for (btn of verificationBtn) {
            btn.addEventListener('click', async (e) => {
                const userId = e.target.dataset.id;
                const verifiedStatus = e.target.dataset.verified;

                const request = await fetch('/admin/users/' + userId, {
                    method: 'PUT',
                    body: JSON.stringify({
                        "request": "verificationChange",
                        'is_verified': verifiedStatus,
                        "user_id" : userId
                    })
                });

                const response = await request.json();

                if (
                    request.status === 202 ||
                    response['isOk']
                ) {
                    e.target.dataset.verified = response['is_verified'];
                    e.target.textContent = response['is_verified'] ? '✅' : '❌';
                }
            })
        }

        const adminBtn = document.querySelectorAll('.js-adminUserBtn');

        for (btn of adminBtn) {
            btn.addEventListener('click', async (e) => {
                const userId = e.target.dataset.id;
                const adminStatus = e.target.dataset.admin;

                const request = await fetch('/admin/users/' + userId, {
                    method: 'PUT',
                    body: JSON.stringify({
                        "request": "adminChange",
                        'is_admin': adminStatus,
                        "user_id" : userId
                    })
                });

                const response = await request.json();

                if (
                    request.status === 202 ||
                    response['isOk']
                ) {
                    e.target.dataset.admin = response['is_admin'];
                    e.target.textContent = response['is_admin'] ? '✅' : '❌';
                }
            })
        }
    </script>

</body>


</html>