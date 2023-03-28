<?php

use Firebase\JWT\JWT;


use Firebase\JWT\Key;
use CodeIgniter\HTTP\Header;

use App\Models\UserModel;
use function PHPUnit\Framework\throwException;

function getJWT($otentikasiHeader)
{
    if (is_null($otentikasiHeader)) {
        throw new Exception("JWT Authentication need Token");
    }
    return explode(" ", $otentikasiHeader)[1];
}

function validateJWT($encodedToken)
{
    $key = getenv('JWT_SECRET_KEY');
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    $UserModel = new UserModel();
    $UserModel->getUsername($decodedToken->username);
}

function createJWT($username)
{
    $waktuRequest = time();
    $waktuToken = getenv('JWT_TIME_TO_LIVE');
    $waktuExp = $waktuRequest + $waktuToken;
    $payload = [
        'username' => $username,
        'iat' => $waktuRequest,
        'exp' => $waktuExp
    ];

    $jwt = JWT::encode($payload, getenv('JWT_SECRET_KEY'), 'HS256');
    return $jwt;
}
