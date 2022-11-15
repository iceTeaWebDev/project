<?php

class Hotel {

    private $conn;
    public $hotel_id;
    public $hotel_name;
    public $hotel_rate;
    public $hotel_address;
    public $hotel_image;
    public $hotel_service;
    public $hotel_description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM hotel";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single() { 
        $query = "SELECT * FROM hotel WHERE ";
    }
}

?>