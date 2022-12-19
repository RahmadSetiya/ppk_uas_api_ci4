<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserModel;

class JWTCI4 extends BaseController {
    private $key;

    private $iss;

    private $ttl; //in minutes

    private $iat;

    private $exp;

    private $nbf;

    private $jti;

    private $userModel;

    public function __construct()
    {
        $this->setConfig()->setExpiredDate();
    }

    protected function setConfig()
    {
        $this->key = getenv("TOKEN_SECRET");
        $this->ttl = getenv("TOKEN_TIME") ? getenv("TOKEN_TIME") : 60;
        $this->iss = $this->getCurrentURL();
        $this->jti = $this->setTime(date("Y-m-d H:i:s"));
        $this->userModel = new UserModel();
        $this->user = $this->userModel->where('user_id', session()->get('user_id'))->first();

        return $this;
    }

    protected function setExpiredDate()
    {
        $now = date("Y-m-d H:i:s");
        $this->iat = $this->setTime($now);
        $this->nbf = $this->setTime($now);
        $this->exp = $this->setTime(date("Y-m-d H:i:s", strtotime("+" . $this->ttl . " MINUTES")));
        return $this;
    }

    public function token($user)
    {
        $payload = [
            'iss' => $this->iss,
            'iat' => $this->iat,
            'exp' => $this->exp,
            'nbf' => $this->nbf,
            'jti' => $this->jti,
            'data' => $user
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function parse($token)
    {

        $bearerToken = $this->getBearerToken($token);
        if (!$bearerToken) return ['success' => false, 'message' => 'Token Invalid'];

        try {
            $decoded = JWT::decode($bearerToken, new Key($this->key, 'HS256'));
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getBearerToken($token)
    {
        $token = explode(" ", $token);
        if (!isset($token[0]) && $token[0] != 'Bearer') {
            return false;
        }

        return $token[1];
    }

    public function setTime($date)
    {
        return strtotime($date);
    }

    public function getCurrentURL()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        return $url;
    }
}
