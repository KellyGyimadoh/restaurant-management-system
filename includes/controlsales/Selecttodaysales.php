<?php
class Selecttodaysales extends Sales{

        
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
    
        public function viewTodaySales(){
            $result=parent::getTodaySales($this->limit,$this->offset,$this->search);
            return $result ? $result : [];
        }
       
       
    
        public function todaySalestotalCount($search){
        
            $result=parent::getAllSalesTodayCount($search);
            return $result ? $result : 0;
        }
    
       
    }
   

