<?php
session_start();
// Conectarea la baza de date
$conn = mysqli_connect("localhost", "root", "Eduard2405!", "proiect_mds");

if($conn === false){
    die("Eroare la conectare. " . mysqli_connect_error());
}

$user_id = $_SESSION['id'];
$user_name = $_SESSION['name'];

$calendar_query = "SELECT u.name AS user_name, c.name AS calendar_name FROM userincalendar uc JOIN calendar c ON c.id = uc.calendarId JOIN user u ON u.id = uc.userId WHERE uc.userId = $user_id";
$calendar_result = mysqli_query($conn, $calendar_query);

// Verifică dacă s-au găsit calendare
if(mysqli_num_rows($calendar_result) > 0){
    // Există calendare la care utilizatorul este implicat, le afișăm
    echo "<h2>Calendarele la care ești implicat:</h2>";
    echo "<ul>";
    while($row = mysqli_fetch_assoc($calendar_result)){
        // Afișează numele utilizatorului și numele calendarului într-un element <p>
        echo "<li><p>".$row['user_name']." - ".$row['calendar_name']."</p></li>";
    }
    echo "</ul>";
} else {
    // Nu există calendare la care utilizatorul este implicat
    echo "<p>Nu ești implicat în niciun calendar.</p>";
}

// Închide conexiunea la baza de date
mysqli_close($conn);
?>
