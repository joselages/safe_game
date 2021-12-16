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

    public function edit($data){
        if(
            mb_strlen($data['username']) >= 2 &&
            mb_strlen($data['username']) <= 15 &&
            mb_strlen($data['password']) >= 8 &&
            mb_strlen($data['password']) <= 1000 &&
            $data['password'] === $data['repeat_password']
        ){
            return true;
        }

        return false;
    }

    public function safeCreate($data){


        if(
            ( //se tiver logged ou se não tiver logged
                ( isset($data['user_id'])  ) ||
                ( isset($data['creator_name']) && mb_strlen($data['creator_name']) >= 2 && mb_strlen($data['creator_name']) <= 15 )
            ) &&
            (//se quiser enviar mail ou não
                ( $data['send-by'] === 'mail' && filter_var($data['email-to'], FILTER_VALIDATE_EMAIL) ) ||
                $data['send-by'] === 'link'
            ) && 
            (//validar mensagem 
                mb_strlen($data['message'])>8 && mb_strlen($data['message'])<140 
            ) &&
            is_numeric($data['code_1']) && //verificar se codigo é um numero
            ( //verificar intervalo dos digitos do codigo
               ( $data['code_1'] >= 0 && $data['code_1'] <= 40 ) &&
               ( $data['code_2'] >= 0 && $data['code_2'] <= 40 ) &&
               ( $data['code_3'] >= 0 && $data['code_3'] <= 40 )
            ) &&
            (//verificar se o codigo é todo o mesmo
                 ($data['code_1'] === $data['code_2']) && ($data['code_2'] === $data['code_3']) 
            ) === false &&
            isset($data['private'])
        ){
            return true;
        }


        return false;

    }

    public function image($file){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileFormat = finfo_file($finfo, $file['tmp_name']);

        if(
            $file['error'] === 0 &&
            $file['size'] > 0 &&
            $file['size'] < 2000000 &&
            in_array($fileFormat, $this->allowedImageFormats)
        ){
            return true;
        }

        return false;
    }
}