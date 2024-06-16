<?php
$conn = mysqli_connect("localhost", "root", "", "proiect_mds");

if($conn === false){
    die("Eroare la conectare. " . mysqli_connect_error());
}
?>