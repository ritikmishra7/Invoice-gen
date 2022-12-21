<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}
$usernamee = $_SESSION['username'];
$correct_email = true;
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

        //INSERTION

        //1 - INSERT INTO USER-ADDITIONAL
        $Bsql = "INSERT INTO `user_additional_info` (`username`, `name`, `email`, `address`, `phone`, `GST`, `inv_id`) VALUES ('$usernamee', '$Bname','$Bemail','$Baddress', '$Bphone', '$BGST', '$Ino')";
        $Bresult = mysqli_query($conn, $Bsql);

        //2 - INSERT INTO CUSTOMER TABLE
        $Csql = "INSERT INTO `cust_info` (`inv_id`, `cname`, `caddress`, `cphone`, `cemail`,  `username`) VALUES ('$Ino', '$Cname', '$Caddress', '$Cphone', '$Cemail', '$usernamee')";
        $Cresult = mysqli_query($conn, $Csql);

        // TOTAL DETAILS
        $subtotal = $_POST["subtotal"];
        $taxtotal = $_POST["taxtotal"];
        $discounttotal = $_POST["discounttotal"];
        $total = $_POST["total"];

        //3 - INSERT INTO TOTAL DETAILS
        $Tsql = "INSERT INTO `total_details` (`inv_id`, `subtotal`, `tax`, `discount`, `total`, `date`, `username`) VALUES ('$Ino', '$subtotal', '$taxtotal', '$discounttotal', '$total', '$Idate', '$usernamee')";
        $Tresult = mysqli_query($conn, $Tsql);

        //4 - INSERT ITEMS

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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="add_invoice_style.css?v=<?php echo time(); ?>">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <title>Add Invoice</title>
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
                            <td><input type="text" name="Bname" id="" placeholder="Business Name"></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="Bemail" id="" placeholder="name@email.com"></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><input type="text" placeholder="Street" name="Baddress"></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td><input type="tel" name="Bphone" id="" placeholder="(123) 456 789"></td>
                        </tr>
                        <tr>
                            <td>GST:</td>
                            <td><input type="text" name="BGST" id="" placeholder="123456789 RT"></td>
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
                            <td><input type="text" name="Cname" id="" placeholder="Customer Name"></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="Cemail" id="" placeholder="name@email.com"></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><input type="text" placeholder="Street" name="Caddress"></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td><input type="tel" name="Cphone" id="" placeholder="(123) 456 789"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>
            <div class="add-box">
                <table>
                    <tr>
                        <td>Invoice Number</td>
                        <td><input type="text" name="inum" id=""></td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td><input type="date" name="date" id=""></td>
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
                        <!-- <th>Tax(%)</th> -->
                        <!-- <th>Amount After Tax(₹)</th> -->
                    </tr>

                    <tr id="row1" class="row1">
                        <td><button class="removerow" id="remove1" type="button" onclick="removeparent(this)"><i
                                    class="fa-solid fa-trash"></i></button> </td>
                        <td><input type="text" name="itemname[]" class="item-name1" placeholder="Item Name">
                        </td>
                        <td style="width: 40%;"><input type="text" name="itemdesc[]" class="item-desc1"
                                placeholder="Item Description here">
                        </td>
                        <td><input type="number" placeholder="0" id="rate1" class="rate1" value="" name="rate[]"></td>
                        <td id="qty"><input type="number" name="qty[]" placeholder="0" id="qty1" class="qty1"></td>
                        <td class="amt"><input type="number" placeholder="₹ 0" id="amt1" class="amt1" name="amount[]"
                                readonly>
                        </td>
                        <!-- <td><input type="text" placeholder="0" name="tax" id="tax1" class="tax1"></td> -->
                        <!-- <td><input type="text" id="amttax1" placeholder="₹ 0" class="amttax1"></td> -->
                    </tr>
                </table>

                <button class="addrow" id="addrow" type="button"><i class="fa-solid fa-plus"></i></button>

            </div>
            <div class="total">
                <table>
                    <tr>
                        <td class="subtotal">Subtotal(₹)</td>
                        <td><input type="text" name="subtotal" id="subtotal" placeholder="₹ 0" readonly></td>
                    </tr>
                    <tr>
                        <td>Tax(%)</td>
                        <td><input type="text" name="taxtotal" id="taxtotal" placeholder="(%)" readonly></td>
                    </tr>
                    <tr>
                        <td>Discount(%)</td>
                        <td><input type="text" name="discounttotal" id="discount" placeholder="(%)"></td>
                    </tr>
                    <tr>
                        <td>Total(₹)</td>
                        <td><input type="text" name="total" id="total" placeholder="₹ 0" readonly></td>
                    </tr>
                </table>
            </div>

            <div class="final-buttons">
                <button type="button" class="cancel-btn" onclick="location.href = '/DBMS_Project/welcome.php';"><i
                        class="fa-solid fa-xmark"></i> Cancel</button>
                <button type="submit" class="insert-btn"><i class="fa-solid fa-plus"></i> Create Invoice</button>
            </div>
        </form>
    </div>
</body>
<script src="invoice_script.js"></script>

</html>