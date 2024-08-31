<?php
class Viewjoined extends JoinMenus{
    
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

    public function viewalljoined(){
        $result=parent::joinAllMenus($this->limit,$this->offset,$this->search);
        return $result ? $result : [];
    }
   /* public function selectTheMenuType(){
        $result=parent::selectAllMenuType();
        if(!empty($result)){
            return $result;
           
        }else{
            $this->errors['emptyinput']="Empty fields";
            $_SESSION['menuselecterror']=$this->errors;
            return [];
            
        }
    }*/
   

    public function totalMenuAllCount(){
        $result=parent::getAllMenuCount();
        return $result ? $result : 0;
    }

   
}