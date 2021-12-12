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
}
