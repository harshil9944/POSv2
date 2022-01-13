<?php
//application/libraries/CreatorJwt.php
require APPPATH . '/libraries/JWT.php';

class Creatorjwt
{


    /*************This function generate token private key**************/

    PRIVATE $key = "1234567890qwertyuiopmnbvcxzasdfghjkl";
    public function generate_token($data)
    {
        $jwt = JWT::encode($data, $this->key);
        return $jwt;
    }


    /*************This function DecodeToken token **************/

    public function decode_token($token)
    {
        $decoded = JWT::decode($token, $this->key, array('HS256'));
        $decodedData = (array) $decoded;
        return $decodedData;
    }
}
