<?php

function deleteFile($filePath){

    if(file_exists($filePath)){
        unlink($filePath);
        return true;
    }

    return false;
}