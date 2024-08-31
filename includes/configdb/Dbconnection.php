<?php
class Dbconnection{
        private $hostname="localhost";
        private $dbname="foodshop";
        private $username="root";
        private $password="";

        protected function connect(){
            try {
                $conn= new PDO("mysql:hostname=$this->hostname;dbname=$this->dbname",$this->username,$this->password);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                return $conn;
            } catch (PDOException $e) {
                die("connection failed".$e->getMessage());
            }
        }

}