<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;

// You can get this from https://<sso-domain>.login.sys.<domain>/token_keys
$publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvF+Yx4eZQdQvXnaHAPHF
T3ji+KvPvRq0Xy5LxVbcjyQnDNMcLx4BkmCcY0aMh834v6LJ24RC8XSrf670QEaJ
FyMIGyKPiin0nm/yxBkym57MZW+C3EkdJ2vKPxDgHMi/Tc6driIU/wtjPFzEJvEh
c9BQC96XY/kGk/Piq4xhwL4kiHv5yzuo5zL1FG5ok05XDAhSSDmEIX5zTtWtmgVG
VVwWOPojW19SanRczFjA0L/zAqv+C2Qdunuycrucn93y1Kf5CsSMiYZ165RhiJov
Br6aRDgsk5kaPH4c7k4L+6yEP8Zf136EkZ64sxLmyCrLtJtRokQAiDfUpwIu4OPb
SwIDAQAB
-----END PUBLIC KEY-----
EOD;

//There's probably libraries that do jwt validation. This is just an example.
if (isset($_SERVER["HTTP_AUTHORIZATION"]) && 0 === stripos($_SERVER["HTTP_AUTHORIZATION"], 'Bearer ')) {
    $jwtString = substr($_SERVER["HTTP_AUTHORIZATION"], 7);
    $jwt = JWT::decode($jwtString, $publicKey, array('RS256'));

    if (jwtIsValid($jwt)) {
        header('Content-Type: application/json; charset=utf-8');        
        $data = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
        echo json_encode($data);
    }
    else {
        echo "Please refresh your token.";
    }

}
else {
    echo "You are not authenticated.";
}

//This is just simple validation. In reality you'd most likely want to validate scopes as well.
function jwtIsValid($jwt) {

    $applicationId = "be92e5a2-e7b1-483b-9b6d-164011d1076a";
    $issuer = "https://<sso-domain>.uaa.sys.<domain>/oauth/token";

    //Validate the audience matches the ApplicationID
    if (!in_array($applicationId, $jwt->aud)) {                
        return false;
    }
    
    //Validate the issuer
    if ($jwt->iss != $issuer) {        
        return false;
    }
    
    //Make sure the token isn't expired
    if (time() > $jwt->exp) {        
        return false;
    }
    
    return true;
}

