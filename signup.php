<?php

include 'db.php';

// Verifică dacă formularul de înregistrare a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Preia datele introduse de utilizator din formular și evită injectarea SQL
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $rpassword = mysqli_real_escape_string($conn, $_POST['password-r']);

    if ($password != $rpassword) {
        echo "Parolele nu sunt identice";
    } elseif (strlen($password) < 4) {
        echo "<script>alert('Parola trebuie sa aiba minim 4 caractere');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresa de email nu este validă.');</script>";
    } else {
        // Verifică dacă există deja un utilizator cu același email
        $check_email_query = "SELECT * FROM user WHERE email='$email'";
        $check_email_result = mysqli_query($conn, $check_email_query);

        if (mysqli_num_rows($check_email_result) > 0) {
            echo "<script>alert('Exista deja un utilizator cu acest email.');</script>";
        } else {
            // Verifică dacă există deja un utilizator cu același nume de utilizator
            $check_user_query = "SELECT * FROM user WHERE username='$username'";
            $check_user_result = mysqli_query($conn, $check_user_query);

            if (mysqli_num_rows($check_user_result) > 0) {
                echo "Acest nume de utilizator este deja folosit.";
            } else {
                // Hash parola utilizând funcția password_hash()
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Inserează utilizatorul în baza de date
                $insert_user_query = "INSERT INTO user (username, password , email) VALUES ('$username', '$hashed_password' , '$email')";
                if (mysqli_query($conn, $insert_user_query)) {
                    echo "<script>alert('Cont creat cu succes'); window.location='loginh.php';</script>";
                } else {
                    echo "Eroare la înregistrare. Încercați din nou mai târziu.";
                }
            }
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffece0;
            margin: 0;
            padding: 0;
            display: grid;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <div>
        <h2>Formular de înregistrare</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Nume Utilizator:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Parolă:</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Rescrie parola:</label>
                <input type="password" name="password-r" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="text" name="email" required>
            </div>
            <div>
                <input type="submit" value="Înregistrare">
            </div>
        </form>
    </div>
</body>

</html>