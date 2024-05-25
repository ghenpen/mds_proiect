<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular de Login</title>
    <!-- Adaugă Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

        .container {
            margin-top: 50px;
            animation: zoomIn 0.5s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0);
            }
<<<<<<< HEAD

=======
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
            to {
                transform: scale(1);
            }
        }

        .signup-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
<<<<<<< HEAD

        .cont {
=======
        .cont{
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #ffece0;
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

        a {
            text-align: center;
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<<<<<<< HEAD

<body>
    <?php include 'header.php'; ?>
    <div class="cont">
        <img src="logo.png" alt="Logo"
            style="display: inline-block; height: 50%;position: relative; right:100px; border-radius: 50px;">
        <div class="container">
            <h2>Formular de Login</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Nume Utilizator:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Parolă:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <a href="../mds_proiect/signup.php" class="signup-link">sign up</a>
        </div>
    </div>
=======
<body>
    <?php include 'header.php'; ?>
    <div class="cont">
    <img src="logo.png" alt="Logo"  style="display: inline-block; height: 50%;position: relative; right:100px; border-radius: 50px;">
    <div class="container">
        <h2>Formular de Login</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Nume Utilizator:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>    
            <div class="form-group">
                <label for="password">Parolă:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <a href="../proiect/signup.php" class="signup-link">sign up</a>
    </div>
    </div>
>>>>>>> 08c1194ee6f4f68964a2e24abb4736b25b72ee6f
    <!-- Adaugă Bootstrap JS pentru funcționalități suplimentare (opțional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>