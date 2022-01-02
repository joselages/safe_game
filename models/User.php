<?php

require_once('Base.php');
require_once(ROOT . 'controllers/helpers/validate.php');


class User extends Base
{

    public function get($id)
    {

        $id = $this->sanitize(["id"=>$id]);
        $id = intval($id["id"]);

        $query = $this->db->prepare("
            SELECT 
                users.user_id, 
                users.username,
                users.created_at,
                users.is_verified,
                (
                    SELECT COUNT(*)
                    FROM safes 
                    WHERE safes.user_id = users.user_id
                ) AS safeCount,
                (
                    SELECT COUNT(*)
                    FROM cracked_safes AS cs 
                    WHERE cs.user_id = users.user_id
                ) AS crackedCount,
                (
                    SELECT COUNT(*)
                    FROM cracked_safes AS cs
                    INNER JOIN safes USING (safe_id)
                    WHERE safes.user_id = users.user_id
                ) AS createdCracked,
                (
                    SELECT SUM(seconds_to_crack)
                    FROM cracked_safes AS cs 
                    WHERE cs.user_id = users.user_id
                ) AS crackingTime
            FROM users
            WHERE user_id = ?;
       ");

        $query->execute([$id]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => 'User not found'
            ];
        }

        if ( $user['is_verified'] === "0" ) {
            return [
                "status" => false,
                "message" => 'User not verified'
            ];
        }

        return [
            "status" => true,
            "message" => 'all ok!',
            'user' => $user
        ];
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
            'message' => 'Please check your email to verify your account',
            'user' => $data
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
            UPDATE users
            SET password = ?
            WHERE user_id = ?;
        ');

       $result = $query->execute([
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

    public function adminGetAll(){

        $query = $this->db->prepare("
            SELECT 
                users.user_id, 
                users.username,
                users.email,
                users.created_at,
                users.is_verified,
                users.is_admin,
                (
                    SELECT COUNT(*)
                    FROM safes 
                    WHERE safes.user_id = users.user_id
                ) AS safeCount
            FROM users;
       ");

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adminDelete($id){
        $id = $this->sanitize(['user_id' => $id]);
        $id = intval($id['user_id']);

        //basta apagar o user pq a tabela safes tem a constraint CASCADE ON DELETE
        $query = $this->db->prepare('
            DELETE FROM users
            WHERE user_id = ?;
        ');


        $result = $query->execute([
            $id
        ]);

        if($result === false){
            http_response_code(500);
            return [
                'isDeleted' => false,
                'message' => 'Something went wrong, please try again later'
            ];
        }

        $deletedRow = $query->rowCount();

        if( $deletedRow !== 1 ){
            http_response_code(400);
            return [
                'isDeleted' => false,
                'message' => 'That user does not exist'
            ];
        }

        http_response_code(202);
        return [
            'isDeleted' => true,
            'message' => 'User '.$id.' was successfully deleted.',
            'user_id' => $id
        ];
    }
}
