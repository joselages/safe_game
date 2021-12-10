<?php

require_once('Base.php');

class User extends Base
{

    public function get($id)
    {

        $query = $this->db->prepare("
            SELECT *
            FROM users
            WHERE user_id = ?
       ");

        $query->execute([$id]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
