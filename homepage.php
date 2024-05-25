<?php
session_start();
include 'db.php';

$user_id = $_SESSION['id'];
$user_name = $_SESSION['username'];


$calendar_query = "SELECT u.username AS user_name, c.name AS calendar_name, uc.calendarId AS calendar_id FROM userincalendar uc JOIN calendar c ON c.id = uc.calendarId JOIN user u ON u.id = uc.userId WHERE uc.userId = $user_id";
$calendar_result = mysqli_query($conn, $calendar_query);
include 'header.php';
echo '<a href="creare_calendar.php"><button id="createButton">+</button></a>';
echo '<div id="tableContainer">';
// Verifică dacă s-au găsit calendare
if (mysqli_num_rows($calendar_result) > 0) {
    // Există calendare la care utilizatorul este implicat, le afișăm
    while ($row = mysqli_fetch_assoc($calendar_result)) {
        // Afișează numele utilizatorului și numele calendarului într-un element <div>
        echo "<a href='calendar.php?calendar_id=$row[calendar_id]'><div class='calendar-button'>";
        echo "<h3>" . $row['user_name'] . " - " . $row['calendar_name'] . "</h3>";
        echo "</div></a>";
    }
} else {
    // Nu există calendare la care utilizatorul este implicat
    echo "<p id='noCalendars'>Nu ești implicat în niciun calendar.</p>";
}
echo '</div>';
// Închide conexiunea la baza de date
mysqli_close($conn);
?>
<script>
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // Utilizatorul a navigat înapoi
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "logout.php", false);  // Sincron - poate afecta performanța
            xhr.send();
        }
    });
</script>
<style>
    #tableContainer {
        position: absolute;
        top: 30vh;
        display: grid;
        width: 80vw;
        align-items: center;
        justify-content: center;
    }

    #spacing {
        top: 30vh;
    }

    .calendar-button {
        background-color: #ffd2c6;
        color: white;
        width: 800px;
        height: 80px;
        text-align: center;
        justify-content: center;
        margin-top: 2vh;
        border-radius: 25px;
    }

    .calendar-button:hover {
        background-color: #ff9b8f;
    }

    #noCalendars {
        text-align: center;
        font-style: italic;
        color: #999;
        top: 20vh;
        position: relative;
    }

    /* Add your CSS styles here */
    #createButton {
        font-size: 5vw;
        top: 15vh;
        position: absolute;
        left: 3vw;
        background-color: #ffd2c6;
        color: white;
        width: 6vw;
    }
</style>
