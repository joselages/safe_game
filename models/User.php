<?php

require_once('Base.php');
require_once(ROOT . 'controllers/validation/validate.php');


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

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) {
            http_response_code(404);
            die('Not found...');
        }

        return $user;
    }

    public function store($data)
    {

        $validate = new Validate();

        $data = $this->sanitize($data);

        if (
            $validate->signup($data) === false
        ) {

            return [
                'isStored' => false,
                'message' => 'Inputs not ok'
            ];
        }

        if ($validate->uniqueFields($data['username'], $data['email']) === false) {

            return [
                'isStored' => false,
                'message' => 'Username or email already exists'
            ];
        }

        $data['verification_code'] = bin2hex(random_bytes(32));

        $query = $this->db->prepare('
            INSERT INTO users (username, email, password, verification_code)
            VALUES(?,?,?,?);
        ');

        $result = $query->execute([
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['verification_code']
        ]);


        if (empty($result)) {

            return [
                'isStored' => false,
                'message' => 'Something went wrong, please try again later'
            ];
        }

        return [
            'isStored' => true,
            'message' => 'Please check your email to verify your account'
        ];
    }

    public function edit($data)
    {
        $data = $this->sanitize($data);

        $validate = new Validate();
        if ($validate->edit($data) === false) {
            http_response_code(400);
            return [
                "isEdited" => false,
                "message" => "Inputs not ok"
            ];
        }

        $query = $this->db->prepare('
            SELECT username
            FROM users
            WHERE username = ? 
        ');

        $query->execute([
            $data['username']
        ]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty($user)){
            return [
                "isEdited" => false,
                "message" => "Username already taken"
            ];
        }

        $query = $this->db->prepare('
            UPDATE users
            SET username = ?, password = ?
            WHERE user_id = ?;
        ');

       $result = $query->execute([
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['user_id']
        ]);

        if($result === false){
            http_response_code(500);
            return [
                "isEdited" => false,
                "message" => 'Something went wrong, please try again later.'
            ];
        }

        return [
            "isEdited" => true,
            "message" => 'Info updated with success!'
        ];
    }

    public function verifyUser($verification_code)
    {


        $sanitizedCode = $this->sanitize(["verification_code" => $verification_code]);
        $verification_code = $sanitizedCode["verification_code"];


        $validate = new Validate();

        if ($validate->verificationCode($verification_code) === false) {
            http_response_code(400);

            return [
                "isVerified" => false,
                "message" => "Wrong code format"
            ];
        }

        $query = $this->db->prepare("
            SELECT email, verification_code
            FROM users
            WHERE verification_code = ? AND is_verified = 0;
        ");

        $query->execute([$verification_code]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) {
            http_response_code(400);

            return [
                "isVerified" => false,
                "message" => "The code is wrong or the account was already verified"
            ];
        }

        $query = $this->db->prepare("
            UPDATE users
            SET is_verified = 1
            WHERE verification_code = ?;
       ");

        $result = $query->execute([$user["verification_code"]]);

        if ($result === false) {
            http_response_code(500);
            return [
                "isVerified" => false,
                "message" => 'Something went wrong, please try again later.'
            ];
        }

        return [
            "isVerified" => true,
            "message" => "The account with the email " . $user['email'] . " has been verified!"
        ];
    }
}
