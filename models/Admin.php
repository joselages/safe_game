<?php

require_once('Base.php');

class Admin extends Base {
    public function getFastestCrack(){
        $query= $this->db->prepare('
            SELECT 
            CONCAT(
                (SELECT username FROM users WHERE users.user_id = sc.user_id),
                " cracked the safe with ID ", 
                safe_id, 
                " in ", 
                seconds_to_crack, 
                " seconds!" 
            ) as finalSentence
            FROM cracked_safes AS sc
            WHERE sc.user_id IS NOT NULL
            ORDER BY sc.seconds_to_crack
            LIMIT 1;
        ');

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getLongestUncracked(){
        $query= $this->db->prepare('
        SELECT
            CONCAT(
                "The oldest uncraked safe is safe ID ", 
                safes.safe_id, 
                " created by ",
                CASE WHEN safes.user_id IS NOT NULL
                    THEN (SELECT users.username FROM users WHERE users.user_id = safes.user_id)
                    ELSE safes.creator_name
                END,
                " in ",
                DATE(safes.created_at)
            ) as finalSentence
        FROM safes
        LEFT JOIN cracked_safes USING(safe_id)
        ORDER BY safes.created_at
        LIMIT 1;
        ');

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserWithMostSafesGenerated(){
        $query= $this->db->prepare('
            SELECT
                CONCAT(
                    users.username,
                    " is the user with the most safes created with a total of ", 
                    count(*), 
                    " safes created since ",
                    DATE(users.created_at)
                ) as finalSentence
            FROM safes
            INNER JOIN users USING(user_id)
            GROUP by user_id
            order by count(*) DESC
            LIMIT 1;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserWithMostSafesCracked(){
        $query= $this->db->prepare('
            SELECT
                CONCAT(
                    users.username,
                    " is the user with the most safes cracked with a total of ", 
                    count(*), 
                    " safes cracked."
                ) as finalSentence
            FROM cracked_safes
            INNER JOIN users USING(user_id)
            GROUP by user_id    
            order by count(*) DESC
            LIMIT 1;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    
    public function getUserCount(){
        $query= $this->db->prepare('
            SELECT
                count(*) as userCount
            FROM users;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getSafeCount(){
        $query= $this->db->prepare('
            SELECT
                count(*) as safeCount
            FROM safes;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getCrackedCount(){
        $query= $this->db->prepare('
            SELECT
                count(*) as crackedCount
            FROM cracked_safes;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getYoungestUser(){
        $query= $this->db->prepare('
            SELECT
                CONCAT(
                    users.username,
                    " is the \"youngest\" user."
                ) as finalSentence
            FROM users
            order by created_at DESC
            LIMIT 1;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getOldestUser(){
        $query= $this->db->prepare('
            SELECT
                CONCAT(
                    users.username,
                    " is the \"oldest\" user."
                ) as finalSentence
            FROM users
            order by created_at
            LIMIT 1;
        ');

        
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

}