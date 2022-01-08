<?php require('templates/head.php'); ?>

<body>

    <?php require('templates/nav.php'); ?>
    <main class="safe safe-form -admin">
        <h1>Admin Stats</h1>

        <div class="admin-stats-container">

            <section class="admin-stats">

                <dl class="admin-info">
                    <?php 
                        foreach($result as $info){
                    ?>
                    <div>
                        <dt><?php echo $info['description'] ?></dt>
                        <dd>
                            <?php
                               foreach($info['stats'] as $key => $value ){
                                   if($value === NULL){
                                    continue;
                                   }
                                echo $value .'<br>';
                               }
                            ?>
                        </dd>
                    </div>
                    <?php 
                        }
                    ?>
                </dl>
            </section>
        </div>
    </main>

</body>


</html>