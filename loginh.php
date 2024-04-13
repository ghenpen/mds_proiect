<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Formular de Login</h2>
<form action="login.php" method="post">
    <div>
        <label>Nume Utilizator:</label>
        <input type="text" name="username" required>
    </div>    
    <div>
        <label>Parolă:</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <input type="submit" value="Login">
    </div>
</form>
<a href="../mds/signup.php">sign up</a>
</body>
</html>
