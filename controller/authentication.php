<?php
require_once('database.php');
require_once('../api-jwt/vendor/autoload.php');
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Authentication extends Database{
    // Register New User
    public function userRegisteration($userFullName, $userEmail, $userPassword) {
        if (!$this->userExist($userEmail)) {
            $sql = "INSERT INTO user(`userFullName`, `userEmail`, `userPass` ) VALUES (:userFullName, :userEmail, :userPassword)";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute(["userFullName" => $userFullName, "userEmail" => $userEmail, "userPassword" => $userPassword]);
            if ($result) {
                return true;
            }
        }
        return false;
    }
    // Check if user already registered
    public function userExist($userEmail) {
        $sql = "SELECT userEmail FROM user WHERE userEmail = :userEmail";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["userEmail" => $userEmail]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($result)) {
            return true;
        }
        return false;
    }
    // Check credential of user login
    public function validCredential($email, $pass, $wantData=0) {
        $sql = "SELECT userID, userEmail, userFullName FROM user WHERE userEmail = :email AND userPass = :pass";
        $stmt =  $this->conn->prepare($sql);
        $stmt->execute(['email' => $email,'pass' => $pass]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row) && $wantData == 1) {
            return $row;
        }else if (is_array($row)) {
            return true;
        }
        return false;
    }
    
    public function generateJWT() {
        $response = array();
        $response['message'] = 'Token not generated.';

        if (isset($_POST['login']) ) {
            $email = $this->cleanTitle($_POST['userEmail']);
            $pass = $this->cleanTitle($_POST['userPass']);

            if ($this->validCredential($email, $pass)) {
                $userData = $this->validCredential($email, $pass, 1);
                $secretKey = "TEST_SECRET_KEY";
                $issuerClaim = "localhost";
                $issuedAt = time();
                $expireClaim = $issuedAt + 60;
                $token = array(
                    'iat' => $issuedAt,
                    'iss' => $issuerClaim,
                    'exp' => $expireClaim,
                    'data' => array(
                        'email' => $email,
                        'pass' => $pass
                    )
                ); 
    
                $jwt = JWT::encode($token, $secretKey);
                $response = array(
                    'message' => 'Token generated successfully.',
                    'userName' => $userData['userFullName'],
                    'email' => $email,
                    'jwt' => $jwt
                );
            }else {
                $response['message'] = 'invalid credential';
            }
        }else {
            $response['message'] = 'Please click on login btn';
        }
        return json_encode($response);
    }

    public function cleanTitle($input) {
        return htmlspecialchars(stripslashes(trim($input)));
    }
}



?>