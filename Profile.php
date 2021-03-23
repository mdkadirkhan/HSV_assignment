<?php
require_once('controller/authentication.php');
header("Content-Type: application/json; charset=UTF-8");

$user = new Authentication;
$userData = json_decode($user->generateJWT());

print_r($user->generateJWT());


?>