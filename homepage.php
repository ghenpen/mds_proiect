<?php
session_start();
include 'db.php';

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: loginh.php");
    exit();
}

$user_id = $_SESSION['id'];
$user_name = $_SESSION['username'];
$_SESSION['show_back_button'] = false;

// Procesare formular pentru adăugarea unui calendar folosind un cod
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calendar_code'])) {
    $calendar_code = trim($_POST['calendar_code']);
    
    // Verificare existența calendarului în baza de date folosind prepared statements
    $stmt = $conn->prepare("SELECT id, adminId FROM calendar WHERE code = ?");
    $stmt->bind_param("s", $calendar_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $calendar_row = $result->fetch_assoc();
        $calendar_id = $calendar_row['id'];
        $admin_id = $calendar_row['adminId'];
        
        // Verificare prietenie folosind prepared statements
        $stmt = $conn->prepare("
            SELECT * FROM friendship 
            WHERE (userId1 = ? AND userId2 = ?) 
               OR (userId1 = ? AND userId2 = ?)
        ");
        $stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id);
        $stmt->execute();
        $friend_result = $stmt->get_result();

        if ($friend_result->num_rows > 0) {
            // Verificare dacă utilizatorul este deja în calendar
            $stmt = $conn->prepare("SELECT * FROM userincalendar WHERE userId = ? AND calendarId = ?");
            $stmt->bind_param("ii", $user_id, $calendar_id);
            $stmt->execute();
            $check_result = $stmt->get_result();
            
            if ($check_result->num_rows == 0) {
                // Adăugare utilizator în calendar
                $stmt = $conn->prepare("INSERT INTO userincalendar (userId, calendarId) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $calendar_id);
                if ($stmt->execute()) {
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
    $stmt->close();
}

// Modificăm interogarea pentru a include numele creatorului calendarului pentru calendarele adăugate prin cod
$stmt = $conn->prepare("
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
        uc.userId = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$calendar_result = $stmt->get_result();
include 'header.php';
?>
<a href="creare_calendar.php"><button id="createButton">+</button></a>
<div id="tableContainer">
<?php
// Verifică dacă s-au găsit calendare
if ($calendar_result->num_rows > 0) {
    // Există calendare la care utilizatorul este implicat, le afișăm
    while ($row = $calendar_result->fetch_assoc()) {
        // Afișează numele creatorului pentru calendarele adăugate prin cod
        $display_name = $row['user_name'] == $user_name ? $row['creator_name'] : $row['user_name'];
        echo "<a href='calendar.php?calendar_id=" . htmlspecialchars($row['calendar_id']) . "'><div class='calendar-button'>";
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
        background-color: pink;
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