<?php
require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdb/Updateuser.php");

if (isset($_POST['save']) || isset($_POST['confirmupdate']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $errors = [];
    $targetFile = null;

    // Handle file upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == UPLOAD_ERR_OK) {
        $targetDir = '../uploads/'; // Ensure this is correct relative to the script
        $targetFile = $targetDir . basename($_FILES["profileImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedExtensions = array("jpeg", "jpg", "png");

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (500KB limit)
        if ($_FILES["profileImage"]["size"] > 500000) {
            $errors[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, $allowedExtensions)) {
            $errors[] = "Sorry, only JPG, JPEG, and PNG files are allowed.";
            $uploadOk = 0;
        }

        // Attempt to upload file if no errors
        if ($uploadOk == 1) {
            if (!move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
                $errors[] = "Sorry, there was an error uploading your file.";
            } else {
                $success = "The file " . htmlspecialchars(basename($_FILES["profileImage"]["name"])) . " has been uploaded.";
                $_SESSION['uploadsuccess'] = $success;
            }
        } else {
            $errors[] = "Sorry, your file was not uploaded.";
        }
        $_SESSION['uploaderrors'] = $errors;
    }

    // Process other form inputs
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    $fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
    $accounttype = filter_input(INPUT_POST, "accounttype", FILTER_SANITIZE_SPECIAL_CHARS);
    $image = $targetFile;

    // Create an instance of Updateuser class and make changes
    $changedUser = new Updateuser($id, $fname, $lname, $email, $phone, $accounttype, $image);
    $changedUser->makeChanges("../../users/profile.php", "../../users/table.php");

} elseif (isset($_POST['confirmuserupdate']) && $_SERVER['REQUEST_METHOD'] === "POST") {

    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    $fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_SPECIAL_CHARS);
    $lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
    $accounttype = filter_input(INPUT_POST, "accounttype", FILTER_SANITIZE_SPECIAL_CHARS);

    //$message = $_POST['confirmuserupdate'];
    $changedUser = new Updateuser($id, $fname, $lname, $email, $phone, $accounttype, $image);
    $changedUser->makeChanges("../../user/userprofile.php", "../../user/home.php");

} else {
    // Invalid request
    die("Invalid request");
}

// Refactored and cleaned up PHP script ends here
?>

/*
 $filename= $_FILES['profileImage']['name'];
        $filesize= $_FILES['profileImage']['size'];
        $filetmpname= $_FILES['profileImage']['tmp_name'];
        $filetype= $_FILES['profileImage']['type'];
        $file_ext=strtolower(end(explode('.',$filename)));
        $expensions= array("jpeg","jpg","png");
        $uploadDir = '../../assets/img/avatars';
        $uploadFile = $uploadDir . basename($filename);
        if(in_array($file_ext,$expensions)===false){
            $error[]="extension not allowed <br>";
        }
        if($filesize>2097152){
            $error[]="size too big";
        }

       
        
    if(empty($error)){
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
            // File upload was successful
            $imagePath = $uploadFile;
        } else {
          
            // Handle file upload error
        }
    }else{
        print_r($error);
    }







try {
    $conn= new PDO("mysql:hostname=localhost;dbname=foodshop",'root','');
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
     
    $query= "UPDATE workers SET firstname=:fname, lastname=:lname, email=:email, phone=:phone, account_type=:accounttype WHERE id=:id";
    $stmt=$conn->prepare($query);
    
    $stmt->bindParam(":id",$id);
    $stmt->bindParam(":fname",$fname);
    $stmt->bindParam(":lname",$lname);
    $stmt->bindParam(":email",$email);
    $stmt->bindParam(":phone",$phone);
    $stmt->bindParam(":accounttype",$accounttype);
    //error_log("Updating worker with ID: {$this->id}, Firstname: {$this->fname}, Lastname: {$this->lname}, Email: {$this->email}, Phone: {$this->phone}, Account Type: {$this->accounttype}");
    if ($stmt->execute()) {
        $_SESSION['updated']=true;// Update was successful
        header("Location: ../../users/profile.php");
    } else {
        $_SESSION['errors']='could not update'; // Update failed
    }

} catch (PDOException $e) {
    die("could not update".$e->getMessage());
}

*/

