<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    include_once('../config/Database.php');
    include_once('../model/hotel.php');

    $db = new Database();
    $connect = $db->connect();

    $hotel = new Hotel($connect);
    $read = $hotel->read();

    $num = $read->rowCount();

    if($num > 0) {
        $hotel_array = [];
        $hotel_array['data'] = [];

        while($row = $read->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $hotel_item = array(
                'hotel_id' => $hotel_id,
                'hotel_name' => $hotel_name,
                'hotel_rate' => $hotel_rate,
                'hotel_address' => $hotel_address,
                'hotel_image' => $hotel_image,
                'hotel_service' => $hotel_service,
                'hotel_description' => $hotel_description
            );
            array_push($hotel_array['data'], $hotel_item);
        }
        echo json_encode($hotel_array);
    }