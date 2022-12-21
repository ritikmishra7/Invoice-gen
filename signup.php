<?php
$showAlert = false;
$showError = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'partials/_dbconnect.php';
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    $existSql = "SELECT * FROM `user_info` WHERE username = '$uname'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        $showError = "Username already Exists";
    } else {
        if ($pass == $cpass) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `user_info` (`s_id`, `username`, `email`, `password`) VALUES (NULL, '$uname', '$email', '$hash');";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
            }
        } else {
            $showError = 'Passwords do not match';
        }
    }
}
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
    <link rel="stylesheet" href="style_signup.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <title>Sign Up</title>
</head>

<body>
    <?php
    if ($showAlert) {
        echo "<div class='smsg'>
                <p><i class='fa-solid fa-circle-check'></i> You are signed up now!</p>
                </div>";
    }
    if ($showError) {
        echo "<div class='emsg'>
            <span><i class='fa-solid fa-triangle-exclamation'></i> Error! </span>" . $showError . "
            </div>";
    }
    ?>

    <div class="navbar">
        <?php
        if($username)
        {
            echo '<h1 class="welcome">Welcome '.$username. '</h1>';
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
                if($username)
                {
                    echo '<li><a href="/DBMS_Project/logout.php" id="logout"><i class="fa-solid fa-power-off"></i> Logout</a></li>';
                }
        ?>
        </ul>
    </div>
    <div class="container">
        <div class="form-section">
            <div class="sign-up-section">
                <h2 class="sign-up">Sign Up</h2>
                <p>
                    Already have an account?
                    <span class="login"><a href="/DBMS_Project/login.php">Log in</a></span>
                </p>
            </div>
            <form action="/DBMS_Project/signup.php" method="post" class="form">
                <div class="name-box box">
                    <label for="name" class="name-label">User Name</label>
                    <input type="text" name="uname" placeholder="Enter Your User Name" class="name-input"
                        autocomplete="off" maxlength="20">
                </div>
                <div class="email-box box">
                    <label for="email" class="email-label" class="email-label">Email Address</label>
                    <input type="email" name="email" id="" placeholder="you@example.com" class="email-input"
                        autocomplete="off" maxlength="50">
                </div>
                <div class="pass-box box">
                    <label for="password" class="pass-label" class="pass-label">Password</label>
                    <input type="password" name="pass" id="" placeholder="Enter 6 characters or more" class="pass-input"
                        autocomplete="off">
                </div>
                <div class="pass-box box">
                    <label for="password" class="pass-label" class="pass-label">Confirm Password</label>
                    <input type="password" name="cpass" id="" placeholder="Enter 6 characters or more"
                        class="pass-input" autocomplete="off">
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>
        <div class="right-img-section">
            <img src="/DBMS_Project/new-user.png" alt="right-img" class="right-img">

        </div>
    </div>
</body>

</html>