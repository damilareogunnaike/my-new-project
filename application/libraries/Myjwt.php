<?php

/**
 * Created by PhpStorm.
 * User: Lahray
 * Date: 6/27/2017
 * Time: 2:56 AM
 * Implementation of JWT. Written to gain deeper insight into JWT workings
 */
require_once "jwt/JWT.php";

use \Firebase\JWT\JWT;

class Myjwt{
    private $secret;

    private $header;

    private $hashing_algorithm;

    public function __construct(){
        $CI = & get_instance();
        $this->secret = $CI->config->item("jwt_secret");

        $this->hashing_algorithm = $CI->config->item("jwt_hashing_algorithm");
        $this->header = [
            "alg" =>  $this->hashing_algorithm,
            "type" => "jwt"
        ];
    }


    public function encode($payload) {
       return JWT::encode($payload, $this->secret, $this->hashing_algorithm);
    }


    public function decode($token) {

    }
}