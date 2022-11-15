<?php

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set("display_errors", 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json, charset=UTF-8');

include_once("../config/Database.php");
include_once("../model/User.php");

$db = new Database();
$connect = $db->connect();
$bill_obj = new User($connect);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $data = json_decode(file_get_contents("php://input"));

    $headers = getallheaders();

    switch ($data->action) {
        case 'CREATE_BILL':
            try {
                $jwt = $headers['Authorization'];

                $secret_key = "icetea123";

                $decode_data = JWT::decode(substr($jwt, 7), new Key($secret_key, 'HS256'));

                http_response_code(200);
                echo json_encode(array( 
                    "status" => 1,
                    "message" => "We got JWT token",
                    "user_data" => $decode_data
                ));
            } catch (Exception $ex) {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => $ex->getMessage()
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
