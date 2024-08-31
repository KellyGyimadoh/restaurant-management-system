<?php
class Selectmenuitem extends MenuItem{
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
    public function selectTheMenuItem(){
        $result=parent::selectAllMenuItems();
        if(!empty($result)){
            return $result;
           
        }else{
            $this->errors['emptyinput']="Empty fields";
            $_SESSION['menuselecterror']=$this->errors;
            return [];
            
        }
    }
     
    public function selectMenuItemWithLimit(){
        $result=parent::viewMenuItemWithLimit($this->limit,$this->offset,$this->search);
        return $result ? $result : [];
    }

    public function totalMenuItemCount(){
        $result=parent::getTotalMenuItemCount();
        return $result ? $result : 0;
    }

   

}