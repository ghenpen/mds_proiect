<?php
$conn = mysqli_connect("localhost", "root", "", "");

if($conn === false){
    die("Eroare la conectare. " . mysqli_connect_error());
}
?>