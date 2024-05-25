<!DOCTYPE html>
<html lang="en">
<<<<<<< HEAD

=======
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <!-- Adaugă Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #ffd2c6;
            color: #fff;
            padding: 10px;
            text-align: center;
<<<<<<< HEAD
            position: absolute;
            top: 0px;
            width: 100%;
            height: 100px;
=======
            position:absolute;
            top:0px;
            width:100%;
            height:100px;
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
        }

        .login-register {
            text-align: right;
<<<<<<< HEAD
            position: relative;
            right: 5vw;
=======
            position:relative;
            right:5vw;
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
            color: #fff;
        }

        .login-register a {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<<<<<<< HEAD

<body>
    <?php
    //session_start(); // Asigură-te că sesiunea este pornită

    include 'db.php';

    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $user_name = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    ?>
    <div class="header">
        <h1 style="display: inline-block; position: relative; left: 0px; top:10px;">GroupCalendar</h1>
        <div class="login-register" style="display: inline-block;left:100px; position:relative;">
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                // Dacă utilizatorul este autentificat, afișăm un mesaj de bun venit și butoanele Home, Friends și Logout
                echo "Bine ai venit, " . $user_name . "!";
                echo '<a href="homepage.php" class="btn btn-outline-light" style="margin-left: 10px;">Home</a>';
                echo '<a href="friends.php" class="btn btn-outline-light" style="margin-left: 10px;">Friends</a>';
                echo '<a href="logout.php" class="btn btn-outline-light" style="margin-left: 10px;">Logout</a>';
            }
            ?>
        </div>
    </div>

    <!-- Adaugă jQuery înainte de Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Adaugă Bootstrap JS pentru funcționalități suplimentare (opțional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

=======
<body>
<?php

$conn = mysqli_connect("localhost", "root", "", "proiect_mds");

if($conn === false){
    die("Eroare la conectare. " . mysqli_connect_error());
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>
<div class="header">
    <h1 style="display: inline-block; position: relative; left: 0px; top:10px;">GroupCalendar</h1>
    <div class="login-register" style="display: inline-block;left:100px; position:relative;">
        <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
            // Dacă utilizatorul este autentificat, afișăm un mesaj de bun venit și un buton de logout
            echo "Bine ai venit, " . $user_name . "!";
            echo '<a href="logout.php" class="btn btn-outline-light" style="margin-left: 10px;">Logout</a>';
        } else {
            // Dacă utilizatorul nu este autentificat, afișăm butoanele de login și register
            echo '<a href="loginh.php" class="btn btn-outline-light">Login</a>';
            echo '<a href="signup.php" class="btn btn-outline-light" style="margin-left: 10px;">Register</a>';
        }
        ?>
    </div>
</div>


<!-- Adaugă Bootstrap JS pentru funcționalități suplimentare (opțional) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
</html>