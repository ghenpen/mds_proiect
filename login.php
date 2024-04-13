<?php

session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("location: homepage.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $conn = mysqli_connect("localhost", "root", "Eduard2405!", "proiect_mds");
    if($conn === false){
        die("Eroare la conectare. " . mysqli_connect_error());
    }
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "SELECT id, name, password FROM user WHERE name = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if(mysqli_num_rows($result) == 1){
        if(password_verify($password, $row['password'])){
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $username;                            
            header("location: homepage.php");
        } else{
            echo "Parolă incorectă.";
        }
    } else{
       echo "Nu există niciun cont cu acest nume de utilizator.";
    }
    
}
mysqli_close($link);
?>