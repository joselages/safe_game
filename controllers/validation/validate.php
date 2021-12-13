<?php
require_once(ROOT .'models/Base.php');


class Validate extends Base{

    public function uniqueFields($username, $email){
        $query = $this->db->prepare('
            SELECT username, email
            FROM users
            WHERE username = ? OR email = ?;
        ');

        $query->execute([$username,$email]);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty($result)){ //ja existe ou um email ou um username igual
            return false;
        }

        return true;

    }

    public function signup($data){
        if(
            mb_strlen($data['username']) >= 2 &&
            mb_strlen($data['username']) <= 15 &&
            filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
            mb_strlen($data['password']) >= 8 &&
            mb_strlen($data['password']) <= 1000 &&
            $data['password'] === $data['repeat_password']
        ){
            return true;
        }

        return false;

    }

    public function verificationCode($code){
        if(
            mb_strlen($code) === 64
        ){
            return true;
        }

        return false;
    }

    public function login($data){
        if(
            filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
            mb_strlen($data['password']) >= 8 &&
            mb_strlen($data['password']) <= 1000
        ){
            return true;
        }

        return false;
    }
}