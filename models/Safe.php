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


    public function store($data, $picture = [])
    {


        $validate = new Validate();

        if (
            !empty($picture) &&
            $validate->image($picture)
        ) {
            $image = $this->saveImage($picture); //cheio
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
