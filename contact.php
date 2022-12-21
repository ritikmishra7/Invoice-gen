<?php
session_start();
$usernamee = false;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $usernamee = $_SESSION['username'];


    include 'partials/_dbconnect.php';
}

$Contact_name = '';
$Contact_email = '';
$Contact_desc = '';

$message_send = '';
$message_bool = false;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST) && $_POST) {
    $Contact_name = $_POST['name'];
    $Contact_email = $_POST['email'];
    $Contact_desc = $_POST['desc'];


    //CHECKING FOR VALID EMAIL USING API
    $curl = curl_init();
    $encoded_email = urlencode($Contact_email);
    curl_setopt_array($curl, [
    CURLOPT_URL => "https://mailcheck.p.rapidapi.com/?domain=" . $encoded_email,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: mailcheck.p.rapidapi.com",
            "X-RapidAPI-Key: 97c485e386msh7aebc117678930bp1c38d9jsn8cb13760201b"
        ],
    ]);

    $correct_email = false;
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $resJSON = json_decode($response);
        if ($resJSON->valid && !($resJSON->block)) {
            if (!$resJSON->disposable) {
                //marking email as correct
                $correct_email = true;


                //insert into database
                $sql = "INSERT INTO `contact_details`(`contact_name`, `contact_desc`, `contact_email`) VALUES ('$Contact_name','$Contact_desc','$$Contact_email')";
                $sqlresult = mysqli_query($conn, $sql);
            }
        }
    }


    if ($correct_email) {
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


            $mail->addAddress($Contact_email, $Contact_name); //temporary my email given uncomment to send to customer email


            $mail->addReplyTo('invoicer.invoice@gmail.com', 'Information');


            $message = 'Hi ' . $Contact_name . ',<br>We have received your query.We will get back to you shortly';

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'Regarding Invoicer contact';
            $mail->Body = $message;
            $mail->AltBody = "Didn't receive this properly! Please contact admin.";

            $mail->send();
            $message_send = 'Your message has been successfully received.';
            $message_bool = true;
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $message_send = "Your message couldn't be delievered.";
        }
    } else {
        $message_send = "Please enter a valid email";
    }

}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_contact.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <title>Contact us</title>
</head>

<body>
    <div class="navbar">
        <?php
        if ($usernamee) {
            echo '<h1 class="welcome">Welcome ' . $usernamee . '</h1>';
        }
        ?>
        <ul>
            <li><img src="/DBMS_Project/logo.png" alt="LOGO" srcset="" class="logo"></li>
            <li><a href="/DBMS_Project/index.php" id="home"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="/DBMS_Project/welcome.php" id="dashboard"><i class="fa-solid fa-table-columns"></i>
                    Dashboard</a>
            </li>
            <li><a href="/DBMS_Project/login.php" id="login"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            </li>
            <li><a href="/DBMS_Project/signup.php" id="signup"><i class="fa-solid fa-user-plus"></i> Sign up</a>
            </li>
            <li><a href="/DBMS_Project/about.php" id="about"><i class="fa-solid fa-address-card"></i> About</a></li>
            <li><a href="/DBMS_Project/contact.php" id="contactus"><i class="fa-solid fa-address-book"></i> Contact
                    Us</a></li>
            <?php
            if ($usernamee) {
                echo '<li><a href="/DBMS_Project/logout.php" id="logout"><i class="fa-solid fa-power-off"></i> Logout</a></li>';
            }
            ?>
        </ul>
    </div>

    <div class="container">
        <div class="left-section">
            <div class="call-section">
                <div class="heading">
                    <i class="fa-solid fa-phone"></i>
                    <p>CALL US</p>
                </div>
                <div class="info">
                    <p>(123)456-789, (123)456-789</p>
                </div>
            </div>
            <div class="location-section">

                <div class="heading">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>LOCATION</p>

                </div>
                <div class="info">
                    <p>123/3 Lorem Road, Ipsum-123456</p>
                </div>
            </div>
            <div class="hours-section">
                <div class="heading">
                    <i class="fa-solid fa-clock"></i>
                    <p>BUSINESS HOURS</p>
                </div>
                <div class="info">
                    <p>Mon to Fri …… 10 am to 8 pm, Sat, Sun ....… Closed</p>
                </div>
            </div>
        </div>
        <div class="right-section">
            <h1>Contact Us</h1>
            <form action="" method="post" class="contact-section">
                <?php
                if ($message_bool)
                    echo '<p style="color:green;">' . $message_send . '</p>';
                else
                    echo '<p style="color:red;">' . $message_send . '</p>';

                ?>
                <input type="text" name="name" id="" placeholder="Enter your Name" class="name-input">
                <input type="text" name="email" id="" placeholder="Enter a valid email address" class="email-input">
                <textarea name="desc" id="" cols="30" rows="10" class="desc-input"></textarea>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>