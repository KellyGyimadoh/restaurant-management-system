<?php
class Joinorders extends Joinedorders{

        
        private $errors=[];
        private $limit;
        private $offset;
        private $search;
    
    
       
    
        public function __construct($limit,$offset,$search)
        {
            $this->limit=$limit;
            $this->offset=$offset;
            $this->search=$search;
        }
    
        public function viewallorders(){
            $result=parent::joinAllOrders($this->limit,$this->offset,$this->search);
            return $result ? $result : [];
        }
       
       
    
        public function totalAllordersCount($search){
        
            $result=parent::getAllOrdersCount($search);
            return $result ? $result : 0;
        }
    
       
    }
   

