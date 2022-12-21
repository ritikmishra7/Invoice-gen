<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';
$usernamee = $_SESSION['username'];



$inv_id = -1;
$what = '';
if (isset($_GET) && $_GET) {
    $inv_id = $_GET['inv_id'];
    $what = $_GET['what'];
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
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="view_style.css?v=<?php echo time(); ?>">
    <title>Invoice
        <?php echo $inv_id ?>
    </title>
</head>

<body>
    <div class="container" id="print-this">
        <div class="add-form">
            <div class="invoice-h1">
                <p>Invoice</p>
            </div>
            <div class="header-info">
                <div class="left-info">
                    <p>From</p>
                    <table>
                        <tr>
                            <td>Name:</td>
                            <td>
                                <?php echo $Bname ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>
                                <?php echo $Bemail ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>
                                <?php echo $Baddress ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>
                                <?php echo $Bphone ?>
                            </td>
                        </tr>
                        <tr>
                            <td>GST:</td>
                            <td>
                                <?php echo $BGST ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="right-info">
                    <p>To</p>
                    <table>
                        <tr>
                            <td>Name:</td>
                            <td>
                                <?php echo $Cname ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>
                                <?php echo $Cemail ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>
                                <?php echo $Caddress ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>
                                <?php echo $Cphone ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>
            <div class="add-box">
                <table>
                    <tr>
                        <td>Invoice Numbe:</td>
                        <td>
                            <?php echo $inv_id ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td>
                            <?php echo $date ?>
                        </td>
                    </tr>
                </table>
            </div>
            <table id="items-table" class="display cell-border">
                <thead>
                    <tr>
                        <th class="no-border"> S.L</th>
                        <th class="no-border"> Item Name</th>
                        <th class="no-border" style="width: 40%;">Description</th>
                        <th class="no-border">Rate(₹)</th>
                        <th class="no-border">QTY</th>
                        <th class="no-border">Amount(₹)</th>
                    </tr>
                </thead>
                <tbody>
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
                                <td class="no-border">' . $num . '</td>
                                <td class="no-border">' . $itemname . '
                                </td>
                                <td class="no-border" style="width: 40%;">' . $itemdesc . '
                                </td>
                                <td class="no-border">' . $itemrate . '
                                </td>
                                <td class="no-border" id="qty">' . $itemqty . '</td>
                                <td class="no-border amt">' . $itemtotal . '
                                </td>
                            </tr>';
                        $num = $num + 1;
                    }
                    ?>
                </tbody>
            </table>
            <footer class="footer">

                <div class="total">
                    <div class="words">
                        <p class="bold">Amount in words:</p>
                        <p id="words-here">One thousand one hundred and one</p>
                    </div>

                    <table id="total-table">
                        <tr>
                            <td class="bold">Taxable Amount(₹)</td>
                            <td>₹
                                <?php echo $subtotal ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Tax(%)</td>
                            <td>
                                <?php echo $tax ?> %
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Discount(%)</td>
                            <td>
                                <?php echo $discount ?> %
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Invoice Total(₹)</td>
                            <td id="total">₹
                                <?php echo $total ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr>
                <div class="signature">
                    <p>___________________________</p>
                    <p class="bold">Authorised signature</p>
                </div>
                <hr>
                <div class="terms-box">
                    <p class="bold">Terms and Conditions:</p>
                    <p>1.Goods once sold will not be taken back or exchanged.</p>
                    <p>2.The product carries only manufacturer’s warranty and no return or exchange will be entertained.
                    </p>
                </div>
            </footer>
        </div>
    </div>
    <!-- for animation purpose -->
    <div class="loading-screen" id="loading-screen">
        <h1 class="title">Emailing</h1>
        <div class="rainbow-marker-loader"></div>
    </div>

    <div class="emailed" id="emailed">
        <i class="fa-solid fa-circle-check fa-8x tick"></i>
        <p class="invoice-emailed-msg">Invoice has been emailed to
            <?php echo $Cname ?>
        </p>
        <p class="redirecting">Redirecting to home <span class="dot-1">.</span><span class="dot-2">.</span><span
                class="dot-3">.</span><span class="dot-4">.</span><span class="dot-5">.</span></p>
    </div>


    <!-- Scripts -->
    <script src="view_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    let inv_id = "<?php echo $inv_id ?>";

    function openpdf() {
        var element = document.getElementById('print-this');
        var opt = {
            margin: 0,

            filename: 'invoice-' + inv_id + '.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'A4',
                orientation: 'portrait'
            }
        };

        const whatToDo = '<?php echo $what ?>';
        const loading = document.getElementById("loading-screen");
        const emailed = document.getElementById("emailed");

        if (whatToDo == 'view') {
            //for viewing
            html2pdf().set(opt).from(element).output('dataurlnewwindow');
            setTimeout(() => {
                window.location.replace("/DBMS_Project/welcome.php");
            }, 100);
        } else if (whatToDo == 'download') {
            //for downloading
            html2pdf().set(opt).from(element).save();
            setTimeout(() => {
                window.location.replace("/DBMS_Project/welcome.php");
            }, 500);

        } else if (whatToDo == 'print') {

            //for printing
            html2pdf().from(element).toPdf().get('pdf').then(function(pdfObj) {
                pdfObj.autoPrint();
                window.open(pdfObj.output('bloburl'), '_blank');
            });
            setTimeout(() => {
                window.location.replace("/DBMS_Project/welcome.php");
            }, 100);
        } else if (whatToDo == 'email') {
            // for emailing
            html2pdf().set(opt).from(element).toPdf().output('datauristring').then(function(pdfAsString) {
                let dataa = {
                    'fileDataURI': pdfAsString,
                    'inv_id': inv_id
                };
                // Animation purpose
                document.getElementById("print-this").style.display = "none";
                loading.style.display = "block";
                $.ajax({
                    url: "../DBMS_Project/send_email.php",
                    type: "POST",
                    data: dataa,
                    success: function(result) {
                        document.getElementById("loading-screen").style.display = "none";
                        emailed.style.display = "flex";
                        setTimeout(() => {
                            window.location.href = '/DBMS_Project/welcome.php';
                        }, 1500);
                        // window.location.href = '/DBMS_Project/welcome.php';
                    }
                });
                console.log(data);
            });
        }
    }

    openpdf();
    </script>
</body>

</html>