<?php

require_once('Base.php');
require_once(ROOT . 'controllers/helpers/validate.php');

class Access extends Base {
    public function login($data){
        $data = $this->sanitize($data);
         
        $validate = new Validate();

        if($validate->login($data) === false){
            http_response_code(400);
            return [
                "isLogged" => false,
                "message" => "Inputs not ok"
            ];
        }

        $query = $this->db->prepare('
            SELECT user_id, username, password, is_verified
            FROM users
            WHERE email = ?;
        ');

        $query->execute([$data['email']]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if(empty($user)){
            return [
                "isLogged" => false,
                "message" => 'No account with that email'
            ];
        }

        if($user["is_verified"] !== "1"){
            return [
                "isLogged" => false,
                "message" => 'Please check your email and verify your account'
            ];
        }

        if(
            password_verify($data['password'],$user['password']) === false
        ){
            return [
                "isLogged" => false,
                "message" => 'Wrong email or password'
            ];
        }

        return[
            "isLogged" => true,
            "message" => $user
        ];
    }
}