<?php
    class Bill {

        private $conn;
        public $bill_id;
        public $bill_user_id;
        public $bill_room_id;
        public $bill_date_start;
        public $bill_date_end;
        public $bill_user_name;
        public $bill_user_tel;
        public $bill_user_email;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function create_bill() {
            $bill_query = "INSERT INTO bill (bill_user_id, bill_room_id, bill_date_start, bill_user_name, bill_user_tel, bill_user_email) 
            VALUES  (:bill_user_id, :bill_room_id, :bill_date_start, :bill_date_end, :bill_user_name, :bill_user_tel, :bill_user_email)";

            $bill_obj = $this->conn->prepare($bill_query);

            $bill_obj->bindValue(':bill_user_id', $this->bill_user_id, PDO::PARAM_STR);
            $bill_obj->bindValue(':bill_room_id', $this->bill_user_id, PDO::PARAM_STR);
            $bill_obj->bindValue(':bill_date_start', $this->bill_user_id, PDO::PARAM_STR);
            $bill_obj->bindValue(':bill_user_name', $this->bill_user_id, PDO::PARAM_STR);
            $bill_obj->bindValue(':bill_user_tel', $this->bill_user_id, PDO::PARAM_STR);
            $bill_obj->bindValue(':bill_user_email', $this->bill_user_id, PDO::PARAM_STR);

            if($bill_obj->execute()) {
                return true;
            }
            return false;
        }
    }