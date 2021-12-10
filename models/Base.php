<?php

class Base
{

    protected $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host='.CONFIG["DB_HOST"].';dbname='.CONFIG["DB_NAME"].';charset='.CONFIG["DB_CHARSET"].';', CONFIG["DB_USERNAME"], CONFIG["DB_PASSWORD"]);
    }
}
