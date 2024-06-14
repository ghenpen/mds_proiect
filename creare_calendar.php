<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: grid;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    </style>
</head>

<body>

    <?php

    $_SESSION['show_back_button'] = false;
    function generateRandomCode($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomCode = '';
        
        // Generăm codul aleatoriu
        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomCode;
    }
    
    $randomCode = generateRandomCode(6);
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id'])) {
        include 'db.php';

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $adminId = $_SESSION['id'];

        $insert_query = "INSERT INTO calendar (name, adminId, code) VALUES ('$name', '$adminId', '$randomCode');";

        if (mysqli_query($conn, $insert_query)) {
            $calendarId = mysqli_insert_id($conn);
            $insert_user_calendar = "INSERT INTO userInCalendar (userId, calendarId) VALUES ('$adminId', '$calendarId')";
            mysqli_query($conn, $insert_user_calendar);
            $message = "Calendar creat cu succes!";
            echo '<script>alert("' . $message . '"); window.location.href = "homepage.php";</script>';
        } else {
            echo "<p>Eroare: nu s-a putut crea calendarul.</p>";
        }
        mysqli_close($conn);
    }
    ?>
    <?php
    include 'header.php';
    ?>

    <div class="form-container">
        <form method="post">
            <label for="name">Nume Calendar:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit">Crează Calendar</button>
        </form>
    </div>
</body>

</html>