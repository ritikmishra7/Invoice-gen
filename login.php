<?php
$login = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';
    $username = $_POST["uname"];
    $password = $_POST["pass"];

    $sql = "Select * from user_info where username='$username'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("location: welcome.php");
            } else {
                $showError = "Invalid Credentials";
            }
        }

    } else {
        $showError = "Invalid Credentials";
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
    <link rel="stylesheet" href="style_login.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <title>Login</title>
</head>

<body>
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
            <div class="login-in-section">
                <h2 class="login-in">Login</h2>
                <p class="signup-box">
                    Don't have an account?
                    <span class="signup"><a href="/DBMS_Project/signup.php">Signup</a></span>
                </p>
                <?php 
                if ($login) {
                    echo '<p class="login-success">Success! You are now logged in.</p>';
                }
                if ($showError) {
                    echo '<p class="error">Error! ' . $showError . '</p>';
                }
                ?>
            </div>
            <form action="/DBMS_Project/login.php" method="post" class="form">
                <div class="name-box box">
                    <label for="name" class="name-label">User Name</label>
                    <input type="text" name="uname" placeholder="Enter Your User Name" class="name-input"
                        autocomplete="off" maxlength="20">
                </div>
                <div class="pass-box box">
                    <label for="password" class="pass-label" class="pass-label">Password</label>
                    <input type="password" name="pass" id="" placeholder="Enter your password" class="pass-input"
                        autocomplete="off">
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>
        <div class="right-img-section">
            <img src="/DBMS_Project/login-img.png" alt="right-img" class="right-img">
        </div>
    </div>
</body>

</html>