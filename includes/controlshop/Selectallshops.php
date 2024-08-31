<?php
class Selectallshops extends Shop{

        
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
    
        public function viewallShops(){
            $result=parent::viewShopWithLimit($this->limit,$this->offset,$this->search);
            return $result ? $result : [];
        }
       
       
    
        public function totalAllShopCount(){
        
            $result=parent::getTotalShopCount();
            return $result ? $result : 0;
        }
    
       
    }
   

