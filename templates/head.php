<!DOCTYPE html>
<html lang="en">
<?php
    $title = $action .' '. $controller;

    if($title === ' '){
        $title = 'Homepage';
    }
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe - <?php echo ucwords($title)?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔓</text></svg>">
    <link href="<?php echo ROOT; ?>/styles/reset.css" rel="stylesheet">
    <link href="<?php echo ROOT; ?>/styles/safe.css" rel="stylesheet">
    <?php 
        if(
           ( $controller === 'safe' && $action === 'create' ) ||
           ( $controller === 'safe' && $action === 'edit' ) ||
           ( $controller === 'safe' && $action === 'show' )
        ){
            echo '<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">';
        }
    ?>
    
</head>