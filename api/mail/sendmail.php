<?php


require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("../../includes/sessions.php");
require_once("../../includes/configdb/Dbconnection.php");
require_once("../../includes/configdb/Selectusers.php");
require_once("../../includes/configdbcust/Ordercustomers.php");
require_once("../../includes/configdbcust/Customers.php");
require_once("../../includes/ordercustomercontroller/Selectordercustomer.php");
require_once("../../includes/configmail/Mail.php");
require_once("../../includes/mailcontrol/Insertmail.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input');
        }

        $sender = filter_var($input['sender'], FILTER_SANITIZE_EMAIL);
        $sendername = filter_var($input['sendername'], FILTER_SANITIZE_SPECIAL_CHARS);
        $recipient = filter_var($input['recipient'], FILTER_SANITIZE_EMAIL);
        $subject = filter_var($input['subject'], FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_var($input['message'], FILTER_SANITIZE_SPECIAL_CHARS);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kelvinmadridstar@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'jeqc ammg wrwc mdjb'; // Replace with your SMTP app password
       // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
       // $mail->SMTPSecure = 'ssl';
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
        $mail->Port = 587;
       // $mail->SMTPDebug = 6;
       $mail->SMTPOptions=array('ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true));
        

        $mail->setFrom($sender, $sendername);
        $accounttype="";
        if ($recipient == 'all') {
            $allworkers = new Selectusers();
            $allemails = $allworkers->selectAllEmail();

            foreach ($allemails as $email) {
                $mail->addBCC($email['email']);
            }
        }else if($recipient=='admin'|| $recipient=='staff'){
            $alladminstaff= new Selectusers();
            $alladminstaffemail=$alladminstaff->selectAllStaffOrAdminEmail($recipient);
            foreach ($alladminstaffemail as $email) {
                $mail->addBCC($email['email']);
            }

        } 
        else if($recipient=='ordercustomers'){
            $limit=$offset=$search="";
            $ordercustomers= new Selectordercustomer($limit,$offset,$search);
            $ordcustemail= $ordercustomers->selectTheOrderCustomers();
            foreach ($ordcustemail as $email) {
                $mail->addBCC($email['email']);
            }
        } else if( $recipient=='bookingcustomers'){
            $allcustomers= new Customers();
            $allcustomeremail= $allcustomers->selectAllCustomersEmail();
            foreach ($allcustomeremail as $email) {
                $mail->addBCC($email['email']);
            }
        } 
        else {
            $mail->addAddress($recipient);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Enable verbose debug output for troubleshooting
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        if ($mail->send()) {
            $addmail = new Insertmail($subject, $recipient, $message);
            $result = $addmail->logTheMail();
            echo json_encode(['success' => true, 'message' => 'Mail sent successfully']);
        } else {
            throw new Exception('Mail could not be sent. Error: ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage().$mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
