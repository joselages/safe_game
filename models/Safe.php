<?php

require_once('Base.php');

class Safe extends Base{
    public function getAllPublic(){

        $query = $this->db->prepare('
            SELECT 
                safes.safe_id,
                CASE
                    WHEN safes.user_id IS NOT NULL 
                        THEN (SELECT users.username FROM users WHERE users.user_id = safes.user_id)
                    ELSE 
                        safes.creator_name  
                END as creator_name
            FROM safes
            WHERE safes.is_private = 0;
        ');

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}