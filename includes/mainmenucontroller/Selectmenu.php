<?php
class Selectmenu extends Menu{
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
    public function selectTheMenuType(){
        $result=parent::selectAllMenuType();
        if(!empty($result)){
            return $result;
           
        }else{
            $this->errors['emptyinput']="Empty fields";
           // $_SESSION['menuselecterror']=$this->errors;
            return [];
            
        }
    }
     
    public function selectMenuWithLimit(){
        $result=parent::viewMenuWithLimit($this->limit,$this->offset,$this->search);
        return $result ? $result : [];
    }

    public function totalMenuCount(){
        $result=parent::getTotalMenuCount();
        return $result ? $result : 0;
    }

   

}