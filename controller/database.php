<?php
    class Database {
        private $db = "mysql:host=localhost;dbname";
        private $DBNAME = "hsv_assignment";
        private $DBUSER = "root";
        private $DBPASS = "";
        public $conn;

        public function __construct() {
            try {
                $this->conn = new PDO("$this->db=$this->DBNAME", $this->DBUSER, $this->DBPASS);
            } catch (PDOException $e) {
                echo "Error: ".$e->getMessage();
                die();
            }
            return $this->conn;
        }
        // Error message alert
        public function showMessage($message) {
            return $message;
        }
    }
?>