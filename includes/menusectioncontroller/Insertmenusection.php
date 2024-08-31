<?php
class Insertmenusection extends Menusection
{
    private $menuid;
    private $section;
    private $errors = [];

    public function __construct($menuid, $section)
    {
        $menuid = filter_var($menuid, FILTER_SANITIZE_NUMBER_INT);
        $this->menuid = $this->SanitizeData($menuid);
        $this->section = $this->SanitizeData($section);
    }

    private function SanitizeData($data)
    {
        $data = trim($data);

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        return $data;
    }
    private function isEmpty()
    {
        if (empty($this->section)) {
            return true;
        } else {
            return false;
        }
    }

    private function notNumber()
    {
        if (!filter_var($this->menuid, FILTER_VALIDATE_INT)) {
            return true;
        } else {
            return false;
        }
    }

    private function sectionExist(){
        $result= parent::checkSectionexist($this->section);
        return $result ? true : false;
    }
    public function insertMenuSection()
    {
        if ($this->isEmpty()) {
            $this->errors[] = "Please enter all fields";
        }
        if (!empty($this->menuid)) {
            if ($this->notNumber()) {
                $this->errors[] = "not a valid id";
            }
        }

        if($this->sectionExist()){
            $this->errors[] = "section exist ";
        }
        /*  if(empty($this->errors)){
             // Check if a description with the same menu type and section already exists
        $existingDescription = $this->checkExistingDescription($this->menuid, $this->section);
    
        // If an existing description is found, return an error
        if ($existingDescription) {
            return ["success" => false, "message" => "Description already exists for this menu type and section"];
        } else {
            // Insert the new description
            $result = parent::insertSection($this->menuid, $this->section);
            if ($result) {
                return ["success" => true, "message" => "Description added successfully"];
            } else {
                return ["success" => false, "message" => "Failed to add description"];
            }
        }
    
        }else{
            
            return ["success"=>false, "message"=>$this->errors];
        }
        exit();  */

        if (empty($this->errors)) {
            $result = parent::insertSection($this->menuid, $this->section);
            if ($result) {
                return $result;
            } else {
                return ["success" => false, "message" => "Failed to excute insert"];
            }
        } else {
            return ["success" => false, "message" => $this->errors];
        }
    }
}
