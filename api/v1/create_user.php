<?php
ini_set("display_errors", 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json, charset=UTF-8');

include_once("../config/Database.php");
include_once("../model/User.php");

$db = new Database();
$connect = $db->connect();
$user_obj = new User($connect);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->username) && !empty($data->email) && !empty($data->tel) && !empty($data->address) && !empty($data->password)) {
        $user_obj->username = $data->username;
        $user_obj->email = $data->email;
        $user_obj->tel = $data->tel;
        $user_obj->address = $data->address;
        $user_obj->password = $data->password;
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
        http_response_code(503);
        echo json_encode(array(
            "status" => 0,
            "message" => "All data needed"
        ));
    }
} else {
    http_response_code(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access denied"
    ));
}
