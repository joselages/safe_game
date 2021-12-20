<?php

class Base
{
    protected $db;
    public $allowedImageFormats = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'webp' => 'image/webp'
    ];
    public $fileFormat;

 
    public function __construct()
    {
        $this->db = new PDO('mysql:host='.CONFIG["DB_HOST"].';dbname='.CONFIG["DB_NAME"].';charset='.CONFIG["DB_CHARSET"].';', CONFIG["DB_USERNAME"], CONFIG["DB_PASSWORD"]);
    }

    public function sanitize($arr){
        foreach($arr as $key => $value){
            $arr[ $key ] = trim(htmlspecialchars(strip_tags($value)));
        }

        return $arr;
    }

    public function saveImage($file){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileFormat = finfo_file($finfo, $file['tmp_name']);

        $filename = date('YmdHis') . '_' . bin2hex(random_bytes(4));
        $extension = '.' . array_search($fileFormat, $this->allowedImageFormats);
        
        $filePath = 'uploads/' . $filename . $extension;

        $isSaved = move_uploaded_file($file['tmp_name'], $filePath);

        return [
            "isSaved" => $isSaved,
            'path' => $filePath
        ];
    }

}
