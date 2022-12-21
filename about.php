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
    <link rel="stylesheet" href="style_about.css?v=<?php echo time(); ?>">
    <script src="https://kit.fontawesome.com/4b7ac0f5f8.js" crossorigin="anonymous"></script>
    <title>About us</title>
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
        <div class="right-img-section">
            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80"
                alt="right-img" class="right-img">
        </div>
        <div class="left-section">
            <h1>About us</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tempora, neque assumenda? Omnis sit quibusdam
                nobis sed necessitatibus ex atque libero delectus quam deleniti illo, explicabo nemo natus labore
                accusantium. Nemo?Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim, rerum explicabo! Iste
                nemo consequatur porro laborum impedit harum autem! Eius, fugiat magni aspernatur, sequi harum quod quis
                non tempore error minus libero quo excepturi expedita a, esse dolorum temporibus doloremque facere
                facilis? Eum, soluta. Ad suscipit maxime ea accusantium illo!</p>
            <button class="submit-btn">Read More</button>
        </div>
    </div>
</body>

</html>