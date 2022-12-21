<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';
$usernamee = $_SESSION['username'];



$inv_id = -1;
if (isset($_GET) && $_GET) {
    $inv_id = $_GET['inv_id'];
}
?>


<?php

// Deleting Business Info
// $Usql = "SELECT * FROM `user_additional_info` WHERE username='$usernamee' and inv_id='$inv_id'";
$Usql = "DELETE FROM `user_additional_info` WHERE username='$usernamee' and inv_id='$inv_id'";
$Uresult = mysqli_query($conn, $Usql);

// Deleting Customer Info
// $Csql = "SELECT * FROM `cust_info` WHERE inv_id='$inv_id'";
$Csql = "DELETE FROM `cust_info` WHERE inv_id='$inv_id'";
$Cresult = mysqli_query($conn, $Csql);

// Deleting Total Details
// $Isql = "SELECT * FROM `total_details` WHERE inv_id='$inv_id'";
$Isql = "DELETE FROM `total_details` WHERE inv_id='$inv_id'";
$Iresult = mysqli_query($conn, $Isql);

// Deleting items details
// $Itemsql = "SELECT * FROM `inv_info` WHERE inv_id='$inv_id'";
$Itemsql = "DELETE FROM `inv_info` WHERE inv_id='$inv_id'";
$itemresult = mysqli_query($conn, $Itemsql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_remove.css?v=<?php echo time(); ?>">
    <title>Remove Invoice</title>
</head>

<body>
    <div class="cont">
        <div class="paper"></div>
        <button>
            <div class='loader'>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>Deleting invoice
        </button>
        <div class="g-cont">
            <div class="garbage"></div>
            <div class="garbage"></div>
            <div class="garbage"></div>
            <div class="garbage"></div>
            <div class="garbage"></div>
            <div class="garbage"></div>
            <div class="garbage"></div>
            <div class="garbage"></div>
        </div>
    </div>

    <script>
    setTimeout(() => {
        window.location.href = '/DBMS_Project/welcome.php';
    }, 2000);
    </script>
</body>

</html>