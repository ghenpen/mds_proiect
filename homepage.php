<?php
session_start();
include 'db.php';

$user_id = $_SESSION['id'];
$user_name = $_SESSION['username'];
$_SESSION['show_back_button'] = false;

// Procesare formular pentru adăugarea unui calendar folosind un cod
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calendar_code'])) {
    $calendar_code = mysqli_real_escape_string($conn, $_POST['calendar_code']);
    
    // Verificare existența calendarului în baza de date
    $code_query = "SELECT id, adminId FROM calendar WHERE code='$calendar_code'";
    $code_result = mysqli_query($conn, $code_query);
    
    if (mysqli_num_rows($code_result) > 0) {
        $calendar_row = mysqli_fetch_assoc($code_result);
        $calendar_id = $calendar_row['id'];
        $admin_id = $calendar_row['adminId'];
        
        // Verificare prietenie
        $friend_query = "
            SELECT * FROM friendship 
            WHERE (userId1 = '$user_id' AND userId2 = '$admin_id') 
               OR (userId1 = '$admin_id' AND userId2 = '$user_id')
        ";
        $friend_result = mysqli_query($conn, $friend_query);

        if (mysqli_num_rows($friend_result) > 0) {
            // Verificare dacă utilizatorul este deja în calendar
            $check_query = "SELECT * FROM userincalendar WHERE userId='$user_id' AND calendarId='$calendar_id'";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) == 0) {
                // Adăugare utilizator în calendar
                $insert_query = "INSERT INTO userincalendar (userId, calendarId) VALUES ('$user_id', '$calendar_id')";
                if (mysqli_query($conn, $insert_query)) {
                    echo '<script>alert("You have been added to the calendar.");</script>';
                } else {
                    echo '<script>alert("Error adding you to the calendar.");</script>';
                }
            } else {
                echo '<script>alert("You are already a member of this calendar.");</script>';
            }
        } else {
            echo '<script>alert("You must be friends with the creator of the calendar to add it.");</script>';
        }
    } else {
        echo '<script>alert("Invalid calendar code.");</script>';
    }
}

// Modificăm interogarea pentru a include numele creatorului calendarului pentru calendarele adăugate prin cod
$calendar_query = "
    SELECT 
        u.username AS user_name, 
        c.name AS calendar_name, 
        uc.calendarId AS calendar_id,
        (SELECT username FROM user WHERE id = c.adminId) AS creator_name
    FROM 
        userincalendar uc 
    JOIN 
        calendar c ON c.id = uc.calendarId 
    JOIN 
        user u ON u.id = uc.userId 
    WHERE 
        uc.userId = $user_id
";
$calendar_result = mysqli_query($conn, $calendar_query);
include 'header.php';
?>
<a href="creare_calendar.php"><button id="createButton">+</button></a>
<div id="tableContainer">
<?php
// Verifică dacă s-au găsit calendare
if (mysqli_num_rows($calendar_result) > 0) {
    // Există calendare la care utilizatorul este implicat, le afișăm
    while ($row = mysqli_fetch_assoc($calendar_result)) {
        // Afișează numele creatorului pentru calendarele adăugate prin cod
        $display_name = $row['user_name'] == $user_name ? $row['creator_name'] : $row['user_name'];
        echo "<a href='calendar.php?calendar_id=$row[calendar_id]'><div class='calendar-button'>";
        echo "<h3>" . htmlspecialchars($display_name) . " - " . htmlspecialchars($row['calendar_name']) . "</h3>";
        echo "</div></a>";
    }
} else {
    // Nu există calendare la care utilizatorul este implicat
    echo "<p id='noCalendars'>Nu ești implicat în niciun calendar.</p>";
}
?>
</div>
<div id="codeFormContainer">
    <form method="post" action="">
        <input type="text" name="calendar_code" placeholder="Enter calendar code" required>
        <button type="submit">Add Calendar</button>
    </form>
</div>
<?php
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
        width: 60vw; /* Ajustare pentru a face loc formularului */
        align-items: center;
        justify-content: center;
    }

    #codeFormContainer {
        position: absolute;
        top: 15vh;
        right: 3vw;
        background-color: #ffd2c6;
        padding: 20px;
        border-radius: 10px;
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

    #createButton {
        font-size: 5vw;
        top: 15vh;
        position: absolute;
        left: 3vw;
        background-color: #ffd2c6;
        color: white;
        width: 6vw;
    }

    #codeFormContainer input {
        width: calc(100% - 20px);
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #codeFormContainer button {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #codeFormContainer button:hover {
        background-color: #0056b3;
    }
</style>