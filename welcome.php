<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php?login=false");
    exit;
}

$usernamee = $_SESSION['username'];
include 'partials/_dbconnect.php';
$total_invoice = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style_welcome.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
    <title>Welcome -
        <?php echo $usernamee ?>
    </title>
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js">
    </script>

</head>

<body>
    <div class="navbar">
        <h1>Welcome
            <?php echo $usernamee ?>
        </h1>
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
            <li><a href="/DBMS_Project/logout.php" id="logout"><i class="fa-solid fa-power-off"></i> Logout</a></li>
        </ul>
    </div>


    <div class="right-section">
        <div class="dashboard-section">
            <div class="left-box">
                <!-- Finding number of invoices -->
                <i class="fa-solid fa-file-invoice fa-lg"></i>
                <?php
                $num_of_invoice_q = "SELECT * FROM `user_additional_info` WHERE username='$usernamee';";
                $result1 = mysqli_query($conn, $num_of_invoice_q);
                $Number_of_invoice = mysqli_num_rows($result1);
                ?>
                <p> Invoices
                    <?php echo "$Number_of_invoice" ?>
                </p>
            </div>

            <div class="middle-box">
                <!-- For finding total amount -->
                <i class="fa-solid fa-arrow-trend-up fa-lg"></i>
                <?php
                $sql2 = "SELECT SUM(total) as final FROM `total_details` WHERE username='$usernamee'";
                $sql2result = mysqli_query($conn, $sql2);

                if (mysqli_num_rows($sql2result) == 1) {

                    while ($row22 = mysqli_fetch_assoc($sql2result)) {
                        $final_amount = $row22['final'];
                        echo '<p>₹ ' . $final_amount . '</p>';
                    }
                }
                ?>

            </div>
            <div class="right-box">
                <!-- for finding number of customers -->
                <i class="fa-solid fa-person fa-lg"></i>
                <?php
                $sql3 = "SELECT * FROM `cust_info` WHERE username='$usernamee' GROUP BY cname;";
                $sql3result = mysqli_query($conn, $sql3);
                $num_of_customers = mysqli_num_rows($sql3result);

                echo "<p> " . $num_of_customers . " Customers</p>";
                ?>
            </div>
        </div>
        <div class="invoice-table">
            <!-- GENERATE PRESENT INVOICES -->

            <table class="invoice-inside hover row-border stripe" id="invoice-inside">

                <thead>
                    <tr>
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Total amount</th>
                        <th>View</th>
                        <th>Print</th>
                        <th>Download</th>
                        <th>Edit</th>
                        <th>Email Invoice</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $Isql = "SELECT * FROM `user_additional_info` WHERE username='$usernamee';";

                    $Iresult = mysqli_query($conn, $Isql);
                    $num = mysqli_num_rows($Iresult);
                    if ($num == 0) {
                        echo "
                            <tr>
                            <td>No data</td>
                            <td>No data</td>
                            <td>No data</td>
                            <td>No data</td>
                            <td><i class='fa-solid fa-file-pdf'></i></td>
                            <td><i class='fa-solid fa-print fa-lg view'></i></td>
                            <td><i class='fa-solid fa-download fa-lg download'></i></td>
                            <td><i class='fa-solid fa-pen-to-square fa-lg edit'></i></td>
                            <td><i class='fa-solid fa-envelope email'></i></td>
                            <td><i class='fa-solid fa-trash remove'></i></td>
                        </tr>";
                    } else {
                        $invoices = [];

                        while ($row = mysqli_fetch_assoc(($Iresult))) {
                            $invoices[] = $row['inv_id'];
                        }

                        $n = count($invoices);

                        for ($i = 0; $i < $n; $i++) {
                            $col1 = $invoices[$i];
                            $col2 = "temp";
                            $col3 = "temp";
                            $col4 = "temp";


                            //Retreiving cust name
                            $Csql = "SELECT cname FROM `cust_info` WHERE inv_id = '$col1'";
                            $Cresult = mysqli_query($conn, $Csql);
                            $num_returned = mysqli_num_rows($Cresult);
                            if ($num_returned == 1) {
                                while ($row2 = mysqli_fetch_assoc($Cresult)) {
                                    $col3 = $row2['cname'];
                                }
                            }

                            //Retreiving total amount
                            $Tsql = "SELECT date,total FROM `total_details` WHERE inv_id='$col1'";
                            $Iresult = mysqli_query($conn, $Tsql);
                            $num2_returned = mysqli_num_rows($Iresult);
                            if ($num2_returned == 1) {
                                while ($row3 = mysqli_fetch_assoc(($Iresult))) {
                                    $col2 = $row3['date'];
                                    $col4 = $row3['total'];
                                }
                            }

                            $total_invoice += $col4;
                            //Printing columns
                            $viewloc = '/DBMS_Project/view_invoice.php?inv_id=' . $col1 . '&what=view';
                            $printloc = '/DBMS_Project/view_invoice.php?inv_id=' . $col1 . '&what=print';
                            $downloadloc = '/DBMS_Project/view_invoice.php?inv_id=' . $col1 . '&what=download';
                            $emailloc = '/DBMS_Project/view_invoice.php?inv_id=' . $col1 . '&what=email';
                            $editloc = '/DBMS_Project/edit_invoice.php?inv_id=' . $col1;
                            $removeloc = '/DBMS_Project/remove_invoice.php?inv_id=' . $col1;
                            $rnum = $i + 1;
                            echo "<tr>
                            <td class='row$rnum' id='row$rnum'>$col1</td>
                            <td>$col2</td>
                            <td>$col3</td>
                            <td class=amt>₹ $col4</td>
                            <td><a href='" . $viewloc . "' class='table-btn'><i class='fa-solid fa-file-pdf view'></i></a></td><td><a href='" . $printloc . "' class='table-btn'><i class='fa-solid fa-print print'></i></a></td>
                            <td><a href='" . $downloadloc . "' class='table-btn'><i class='fa-solid fa-download fa-lg download'></i></a></td>
                            <td><a href='" . $editloc . "' class='table-btn'><i class='fa-solid fa-pen-to-square fa-lg edit'></i></a></td>
                            <td><a href='" . $emailloc . "' class='table-btn' id='$rnum'><i class='fa-solid fa-envelope email'></i></a></td>
                            <td><a class='table-btn' id='$rnum' onclick='removedetails(this)'><i class='fa-solid fa-trash remove'i></i></a></td>
                        </tr>";
                        }
                    }

                    ?>
                </tbody>
            </table>
        </div>
        <button class="add_invoice_btn" onclick="location.href = '/DBMS_Project/invoice.php';"><i
                class="fa-solid fa-plus"></i> Add Invoice</button>
    </div>
</body>
<!-- <a href='".$removeloc."' class='table-btn'> -->
<script src="welcome.js">
</script>


</html>