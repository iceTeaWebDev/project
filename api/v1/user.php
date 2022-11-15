<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


require '../vendor/autoload.php';

use \Firebase\JWT\JWT;

ini_set("display_errors", 1);



include_once("../config/Database.php");
include_once("../model/User.php");

$db = new Database();
$connect = $db->connect();
$user_obj = new User($connect);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $data = json_decode(file_get_contents("php://input"));

    switch ($data->action) {
        case 'CREATE_USER':
            $data = $data->data;
            if (!empty($data->username) && !empty($data->email) && !empty($data->tel) && !empty($data->address) && !empty($data->password)) {
                $user_obj->username = $data->username;
                $user_obj->email = $data->email;
                $user_obj->tel = $data->tel;
                $user_obj->address = $data->address;
                $user_obj->password = $data->password;
                $userdata = $user_obj->check_username();
                if (empty($userdata)) {
                    if ($user_obj->create_user()) {
                        http_response_code(200);
                        echo json_encode(array(
                            "status" => 1,
                            "message" => "user have been created"
                        ));
                    } else {
                        http_response_code(500);
                        echo json_encode(array(
                            "status" => 0,
                            "message" => "Failed to save user"
                        ));
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "username already exists"
                    ));
                }
            } else {
                http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "All data needed"
                ));
            }
            break;
        case 'UPDATE_USER':

            break;
        case 'DELETE_USER':

            break;
        case 'LOGIN':
            $data = $data->data;
            if (!empty($data->username) && !empty($data->password)) {

                $user_obj->username = $data->username;
                $user_obj->password = $data->password;

                $user_data = $user_obj->check_login();

                if (!empty($user_data)) {
                    $iss = 'localhost';
                    $iat = time();
                    $nbf = $iat + 1;
                    $exp = $iat + 3600;
                    $secret_key = "icetea123";

                    $payload_info = array(
                        "iss" => $iss,
                        "iat" => $iat,
                        "nbf" => $nbf,
                        "exp" => $exp,
                        "data" => array(
                            "user_id" => $user_data['user_id'],
                            "username" => $user_data['username']
                        )
                    );

                    $jwt = JWT::encode($payload_info, $secret_key, 'HS256');

                    http_response_code(200);
                    echo json_encode(array(
                        "status" => 1,
                        "accessToken" => $jwt,
                        "role" => $user_data["role"],
                        "username" => $user_data["username"],
                        "message" => "User Logged in successfully"
                    ));
                } else {
                    http_response_code(404);
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "Invalid credentials"
                    ));
                }
            } else {
                http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "All data needed"
                ));
            }
            break;

        default:
            # code...
            break;
    }
} else {
    http_response_code(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access denied"
    ));
}
