<?php
session_start();
$username = false;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $username = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style_index.css?v=<?php echo time(); ?>">
    <title>Home</title>
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    </script>

</head>

<body>
    <div class="navbar">
        <?php 
            if($username)
            echo '<h1>Welcome '.$username.'</h1>'
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
                if($username)
                {
                    echo '<li><a href="/DBMS_Project/logout.php" id="logout"><i class="fa-solid fa-power-off"></i> Logout</a></li>';
                }
        ?>
        </ul>
    </div>

    <div class="container">
        <div class="upper-head">
            <h1 class="invoicer-brand">INVOICE GEN</h1>
            <h1>Invoice Automation</h1>
            <h1>Now as simple as ABCD.</h1>
        </div>

        <div class="steps">
            <div class="a section">
                <div class="letter letter-a">
                    <i class="fa-solid fa-a fa-3x"></i>
                </div>
                <p class="subtitle">Acquire Account</p>
            </div>
            <i class="fa-solid fa-arrow-right fa-3x"></i>
            <div class="b section">
                <div class="letter letter-b">
                    <i class="fa-solid fa-b fa-3x"></i>
                </div>
                <p class="subtitle">Begin Account</p>
            </div>
            <i class="fa-solid fa-arrow-right fa-3x"></i>
            <div class="c section">
                <div class="letter letter-c">
                    <i class="fa-solid fa-c fa-3x"></i>
                </div>
                <p class="subtitle">Create Invoice</p>
            </div>
            <i class="fa-solid fa-arrow-right fa-3x"></i>
            <div class="d section">
                <div class="letter letter-d">
                    <i class="fa-solid fa-d fa-3x"></i>
                </div>
                <p class="subtitle">Delight Customers</p>
            </div>
        </div>
        <p class="effortless">Effortlessly create and manage your invoices.</p>
        <div class="redirect-section">
            <a href="/DBMS_Project/signup.php" class="signup-btn">Sign up Now !</a>
            <div class="already">
                <p>Already signed up?</p>
             <a href="/DBMS_Project/login.php" class="login-btn">Log in</a>
            </div>
        </div>
    </div>
</body>

</html>