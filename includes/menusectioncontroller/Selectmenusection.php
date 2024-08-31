<?php
class Selectmenusection extends Menusection{
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
    public function selectTheMenuSection(){
        $result=parent::selectAllMenuSection();
        if(!empty($result)){
            return $result;
           
        }else{
            //$this->errors['emptyinput']="Empty fields";
            //$_SESSION['menusectionerror']=$this->errors;
            return [];
            
        }
    }
     
    public function selectMenuSectionWithLimit(){
        $result=parent::viewMenuSectionWithLimit($this->limit,$this->offset,$this->search);
        return $result ? $result : [];
    }

    public function totalMenuSectionCount(){
        $result=parent::getTotalMenuSectionCount();
        return $result ? $result : 0;
    }

  
}