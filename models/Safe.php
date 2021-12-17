<?php

require_once('Base.php');
require_once(ROOT . 'controllers/validation/validate.php');

class Safe extends Base
{
    public function getAllPublic()
    {

        /* FALTA RETORNAR O NUMERO DE VEZES Q O COFRE FOI ABERTO */

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

    public function get($id){

        $id = $this->sanitize(["id"=>$id]);
        $id = intval($id["id"]);

        if ($id < 0) {
            http_response_code(400);
            return [
                "isOk" => false,
                "message" => "Bad request"
             ];
        }

        $query= $this->db->prepare('
            SELECT
                CASE 
                    WHEN safes.user_id IS NOT NULL 
                        THEN (SELECT users.username FROM users WHERE users.user_id = safes.user_id)
                    ELSE 
                         safes.creator_name  
                END AS safe_creator,
                safes.created_at,
                CONCAT(codes.code_1,"/",codes.code_2,"/",codes.code_3) AS code,
                IF(safes.user_id IS NOT NULL, safes.user_id, 0) AS user_id
            FROM safes 
            INNER JOIN codes USING (safe_id)
            WHERE safes.safe_id = ?;
        ');

        $query->execute([
            $id
        ]);

        $safe = $query->fetch(PDO::FETCH_ASSOC);

        if(empty($safe)){
            http_response_code(404);
            return [
               "isOk" => false,
               "message" => "Safe not found"
            ];
        }

        $safe['code'] = explode('/',$safe['code']);

        return [
            "isOk" => true,
            "message" => "all ok!",
            "safe" => $safe
         ];
    }

    public function openSafe($data){

        $data['safe_id'] = intval($data['safe_id']);
        $data['code_1'] = intval($data['code_1']);
        $data['code_2'] = intval($data['code_2']);
        $data['code_3'] = intval($data['code_3']);

        $query= $this->db->prepare('
            SELECT 
                safes.message,
                safes.image_path
            FROM codes
            INNER JOIN safes USING (safe_id)
            WHERE 
                codes.safe_id = ? AND
                codes.code_1 = ? AND
                codes.code_2 = ? AND
                codes.code_3 = ?;
       ');

        $query->execute([
            $data['safe_id'],
            $data['code_1'],
            $data['code_2'],
            $data['code_3']
        ]);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            return[
                'status'=>false,
                'message' => 'Wrong code'
            ];
        }

        return[
            'status'=>true,
            'message' => 'Safe open!',
            'content' => $result
        ];
    }


    public function store($data, $picture = [])
    {


        $validate = new Validate();

        if (
            !empty($picture) &&
            $validate->image($picture)
        ) {
            $image = $this->saveImage($picture); //cheio
        } else {
            http_response_code(400);
            return [
                "safeCreated" => false,
                "message" => "Image not allowed"
            ];
        }

        

        if (
            (!empty($picture) && empty($image)) ||
            (!empty($image) && $image['isSaved'] === false )
        ) {
            http_response_code(500);
            return [
                "safeCreated" => false,
                "message" => "Error on uploading image"
            ];
        }

        $data = $this->sanitize($data);

        //cast
        $data['private'] = ($data['private'] === 'true') ? true : false;
        $data['code_1'] = intval($data['code_1']);
        $data['code_2'] = intval($data['code_2']);
        $data['code_3'] = intval($data['code_3']);
        
        
        if ($validate->safeCreate($data) === false) {
            http_response_code(400);
            return [
                "safeCreated" => false,
                "message" => "Inputs not ok"
            ];
        }

        //inserir tudo na BD
        $query = $this->db->prepare('
            INSERT INTO safes 
            (user_id,message,image_path,creator_name, is_private)
            VALUES (?,?,?,?,?)
        ');

        $result = $query->execute([
            isset($data['user_id']) ? $data['user_id'] : NULL,
            $data['message'],
            !empty($image) ? $image['path'] : NULL,
            isset($data['creator_name']) ? $data['creator_name'] : NULL,
            $data['private'] ? 1 : 0,
        ]);

        if ($result === false) {
            http_response_code(500);
            return [
                "safeCreated" => false,
                "message" => "Something went wrong, please try again later"
            ];
        }

        $safeId = $this->db->lastInsertId();

        $query = $this->db->prepare('
            INSERT INTO codes 
            (safe_id,code_1,code_2,code_3)
            VALUES (?,?,?,?);
        ');

        $result = $query->execute([
            $safeId,
            $data['code_1'],
            $data['code_2'],
            $data['code_3'],
        ]);

        if ($result === false) {
            http_response_code(500);
            return [
                "safeCreated" => false,
                "message" => "Something went wrong, please try again later"
            ];
        }

        http_response_code(201);
        return [
            "safeCreated" => true,
            "message" => "all ok!",
            "safe_id" => $safeId,
            "code" => [$data['code_1'], $data['code_2'], $data['code_3']],
            "private" => $data['private']
        ];
    }
}
