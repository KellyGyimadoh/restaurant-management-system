<?php
class Paymentsales extends Paymentorders{
   

   

   
    public function getAllSales(){
        $result= parent::getSalesData();
        return $result ? $result : [];
    }

    

}