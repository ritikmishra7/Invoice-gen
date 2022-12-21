<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';
$correct_email = true;
$usernamee = $_SESSION['username'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';

    // FROM DETAILS
    $Bname = $_POST["Bname"];
    $Bemail = $_POST["Bemail"];
    $Baddress = $_POST["Baddress"];
    $Bphone = $_POST["Bphone"];
    $BGST = $_POST["BGST"];


    // TO DETAILS
    $Cname = $_POST["Cname"];
    $Cemail = $_POST["Cemail"];
    $Caddress = $_POST["Caddress"];
    $Cphone = $_POST["Cphone"];

    $curl = curl_init();
    $encoded_email = urlencode($Cemail);
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
            } else {
                $correct_email = false;
            }
        } else
            $correct_email = false;
    }



    if ($correct_email) {
        //ADDIIIONAL BOX
        $Ino = $_POST["inum"];
        $Idate = date('Y-m-d', strtotime($_POST['date']));

        //Updation

        //1 - Updation INTO USER-ADDITIONAL
        $Bsql = "UPDATE `user_additional_info` SET `username` = '$usernamee',`name` = '$Bname', `email` = '$Bemail', `address` = '$Baddress', `phone` = '$Bphone', `GST` = '$BGST' WHERE `user_additional_info`.`inv_id` = '$Ino'";
        $Bresult = mysqli_query($conn, $Bsql);

        //2 - Updation INTO CUSTOMER TABLE
        $Csql = "UPDATE `cust_info` SET `cname` = '$Cname',`caddress` = '$Caddress', `cphone` = '$Cphone',`cemail` = '$Cemail' WHERE `cust_info`.`inv_id` = '$Ino'";
        $Cresult = mysqli_query($conn, $Csql);



        // TOTAL DETAILS
        $subtotal = $_POST["subtotal"];
        $taxtotal = $_POST["taxtotal"];
        $discounttotal = $_POST["discounttotal"];
        $total = $_POST["total"];

        //3 - Updation INTO TOTAL DETAILS
        $Tsql = "UPDATE `total_details` SET `subtotal` = '$subtotal', `tax` = '$taxtotal', `discount` = '$discounttotal',`total` = '$total', `date` = '$Idate' WHERE `total_details`.`inv_id` = '$Ino'";
        $Tresult = mysqli_query($conn, $Tsql);

        //4 - Updation ITEMS

        $Itemsql = "DELETE FROM `inv_info` WHERE inv_id='$Ino'";
        $itemresult = mysqli_query($conn, $Itemsql);

        $itemname = $_POST["itemname"];
        $itemdesc = $_POST["itemdesc"];
        $rate = $_POST["rate"];
        $qty = $_POST["qty"];
        $amt = $_POST["amount"];

        $n = count($itemname);
        for ($i = 0; $i < $n; $i++) {

            $Isql = "INSERT INTO `inv_info` (`inv_id`, `item_name`, `item_desc`, `item_rate`, `item_qty`, `item_total`) VALUES ('$Ino', '$itemname[$i]', '$itemdesc[$i]', '$rate[$i]', '$qty[$i]', '$amt[$i]')";

            $Iresult = mysqli_query($conn, $Isql);
        }

        header("location: welcome.php");
    }
}


$inv_id = -1;
if (isset($_GET) && $_GET) {
    $inv_id = $_GET['inv_id'];
}
?>

<?php

// Extracting Business Info
$Usql = "SELECT * FROM `user_additional_info` WHERE username='$usernamee' and inv_id='$inv_id'";
$Uresult = mysqli_query($conn, $Usql);
$Bname = "";
$Bemail = "";
$Baddress = "";
$Bphone = "";
$BGST = "";

while ($row = mysqli_fetch_assoc($Uresult)) {
    $Bname = $row['name'];
    $Bemail = $row['email'];
    $Baddress = $row['address'];
    $Bphone = $row['phone'];
    $BGST = $row['GST'];
}

// Extracting Customer Info
$Csql = "SELECT * FROM `cust_info` WHERE inv_id='$inv_id'";
$Cresult = mysqli_query($conn, $Csql);

$Cname = "";
$Caddress = "";
$Cphone = "";
$Cemail = "";

while ($row2 = mysqli_fetch_assoc($Cresult)) {
    $Cname = $row2['cname'];
    $Caddress = $row2['caddress'];
    $Cphone = $row2['cphone'];
    $Cemail = $row2['cemail'];
}



// Extracting Total Details
$Isql = "SELECT * FROM `total_details` WHERE inv_id='$inv_id'";
$Iresult = mysqli_query($conn, $Isql);

$subtotal = "";
$tax = "";
$discount = "";
$total = "";
$date = "";

while ($row3 = mysqli_fetch_assoc($Iresult)) {
    $subtotal = $row3['subtotal'];
    $tax = $row3['tax'];
    $discount = $row3['discount'];
    $total = $row3['total'];
    $date = $row3['date'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="edit_invoice_style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <title>Edit Invoice</title>
</head>

<body>
    <div class="container">
        <form action="" method="post" class="add-form">
            <input type="text" name="InvoiceName" id="" value="Invoice" class="invoice-h1" disabled>
            <div class="header-info">
                <div class="left-info">
                    <p>From</p>
                    <table>
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="Bname" id="" placeholder="Business Name"
                                    value="<?php echo $Bname ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="Bemail" id="" placeholder="name@email.com"
                                    value="<?php echo $Bemail ?>"></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><input type="text" placeholder="Street" name="Baddress" value="<?php echo $Baddress ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td><input type="tel" name="Bphone" id="" placeholder="(123) 456 789"
                                    value="<?php echo $Bphone ?>"></td>
                        </tr>
                        <tr>
                            <td>GST:</td>
                            <td><input type="text" name="BGST" id="" placeholder="123456789 RT"
                                    value="<?php echo $BGST ?>"></td>
                        </tr>
                    </table>
                </div>

                <?php
                if ($correct_email == false) {
                    echo '<p style="color:red;">Incorrect Customer email for sending invoice to customer</p>';
                }
                ?>

                <div class="right-info">
                    <p>To</p>
                    <table>
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="Cname" id="" placeholder="Customer Name"
                                    value="<?php echo $Cname ?>"></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="Cemail" id="" placeholder="name@email.com"
                                    value="<?php echo $Cemail ?>"></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><input type="text" placeholder="Street" name="Caddress" value="<?php echo $Caddress ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td><input type="tel" name="Cphone" id="" placeholder="(123) 456 789"
                                    value="<?php echo $Cphone ?>"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>
            <div class="add-box">
                <table>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="inum" id="" value="<?php echo $inv_id ?>"></td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td><input type="date" name="date" id="" value="<?php echo $date ?>"></td>
                    </tr>
                </table>
            </div>


            <div class="items">
                <table id="items-table">
                    <tr>
                        <th></th>
                        <th> Item Name</th>
                        <th style="width: 40%;">Description</th>
                        <th>Rate(₹)</th>
                        <th>QTY</th>
                        <th>Amount(₹)</th>
                    </tr>

                    <!-- adding the invoices already present -->
                    <?php
                    $Itemsql = "SELECT * FROM `inv_info` WHERE inv_id='$inv_id'";
                    $itemresult = mysqli_query($conn, $Itemsql);
                    $num = 1;
                    while ($row4 = mysqli_fetch_assoc($itemresult)) {

                        $itemname = $row4['item_name'];
                        $itemdesc = $row4['item_desc'];
                        $itemrate = $row4['item_rate'];
                        $itemqty = $row4['item_qty'];
                        $itemtotal = $row4['item_total'];

                        echo '<tr id="row' . $num . '" class="row' . $num . '">
                                <td><button class="removerow" id="remove' . $num . '" type="button" onclick="removeparent(this)"><i
                                            class="fa-solid fa-trash"></i></button> </td>
                                <td><input type="text" name="itemname[]" class="item-name' . $num . '" placeholder="Item Name" value="' . $itemname . '">
                                </td>
                                <td style="width: 40%;"><input type="text" name="itemdesc[]" class="item-desc' . $num . '"
                                        placeholder="Item Description here" value="' . $itemdesc . '">
                                </td>
                                <td><input type="text" placeholder="0" id="rate' . $num . '" class="rate' . $num . '" name="rate[]" value="' . $itemrate . '"></td>
                                <td id="qty"><input type="text" name="qty[]" placeholder="0" id="qty' . $num . '" class="qty' . $num . '" value="' . $itemqty . '"></td>
                                <td class="amt"><input type="text" placeholder="₹ 0" id="amt' . $num . '" class="amt' . $num . '" name="amount[]"
                                readonly>
                                </td>
                            </tr>';
                        $num = $num + 1;
                    }
                    ?>
                </table>

                <button class="addrow" id="addrow" type="button"><i class="fa-solid fa-plus"></i></button>

            </div>
            <div class="total">
                <table>
                    <tr>
                        <td class="subtotal">Subtotal(₹)</td>
                        <td><input type="text" name="subtotal" id="subtotal" placeholder="₹ 0" readonly
                                value="<?php echo $subtotal ?>"></td>
                    </tr>
                    <tr>
                        <td>Tax(%)</td>
                        <td><input type="text" name="taxtotal" id="taxtotal" placeholder="(%)" readonly
                                value="<?php echo $tax ?>"></td>
                    </tr>
                    <tr>
                        <td>Discount(%)</td>
                        <td><input type="text" name="discounttotal" id="discount" placeholder="(%)"
                                value="<?php echo $discount ?>"></td>
                    </tr>
                    <tr>
                        <td>Total(₹)</td>
                        <td><input type="text" name="total" id="total" placeholder="₹ 0" readonly
                                value="<?php echo $total ?>"></td>
                    </tr>
                </table>
            </div>

            <div class="final-buttons">
                <button type="button" class="cancel-btn" onclick="location.href = '/DBMS_Project/welcome.php';"><i
                        class="fa-solid fa-xmark"></i> Cancel</button>
                <button type="submit" class="insert-btn"><i class="fa-solid fa-plus"></i> Update Invoice</button>
            </div>
        </form>
    </div>
</body>
<script src="edit_script.js"></script>

</html>