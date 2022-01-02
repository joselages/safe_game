<!DOCTYPE html>
<html lang="pt">

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
                                <span>User desde:<p><?php echo $user['created_at']; ?></p></span>
                                <span>Cofres: <p><?php echo $user['safeCount']; ?></p></span>

                                <div class="flex space-between">
                                    <span>Verified:<span><?php echo $user['is_verified'] ? '✅' : '❌'; ?></span></span>
                                    <span>Admin:<span><?php echo $user['is_admin'] ? '✅' : '❌'; ?></span></span>
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
    </script>

</body>


</html>