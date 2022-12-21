<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}


$usernamee = $_SESSION['username'];
include 'partials/_dbconnect.php';

$inv_id = $_POST['inv_id'];
$Csql = "SELECT cname,cemail FROM `cust_info` WHERE inv_id='$inv_id'";
$Cresult = mysqli_query($conn, $Csql);
$Cname = "";
$Cemail = "";


while ($row2 = mysqli_fetch_assoc($Cresult)) {
    $Cname = $row2['cname'];
    $Cemail = $row2['cemail'];
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// print_r($_POST);
$pdfdoc = $_POST['fileDataURI'];
$b64file = trim(str_replace('data:application/pdf;filename=generated.pdf;base64,', '', $pdfdoc));
// $b64file = str_replace(' ', '+', $b64file);
$decoded_pdf = base64_decode($b64file);

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'invoicer.invoice@gmail.com'; //SMTP username
    $mail->Password = 'kcrgwwjmiehybdjj'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('noreply@invoicer.tk', 'Invoicer');


    // $mail->addAddress($Cemail, $Cname); //Add a recipient
    $mail->addAddress('rritikmmishra@gmail.com', 'Ritik Mishra'); //temporary my email given uncomment to send to customer email


    $mail->addReplyTo('invoicer.invoice@gmail.com', 'Information');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

    $message = 'Hi ' . $Cname . ',<br>Here is your invoice with invoice id ' . $inv_id . ' attached as a pdf.<br>Thank you!';

    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->Subject = 'INVOICE - ' . $inv_id;
    $mail->Body = $message;
    $mail->addStringAttachment($decoded_pdf, 'INVOICE-' . $inv_id . '.pdf');
    $mail->AltBody = "Didn't receive this properly! Please contact admin.";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>