<?php
// Include fișierul de conexiune la baza de date
include 'db.php';

// Verifică dacă a fost trimisă cererea POST cu datele necesare
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["calendar_id"]) && isset($_POST["user_id"]) && isset($_POST["comment"])) {
    $calendarId = $_POST['calendar_id'];
    $userId = $_POST['user_id'];
    $comment = $_POST['comment'];

    // Escapare pentru a preveni injecțiile SQL
    $calendarId = mysqli_real_escape_string($conn, $calendarId);
    $userId = mysqli_real_escape_string($conn, $userId);
    $comment = mysqli_real_escape_string($conn, $comment);
    $created_at = date('Y-m-d H:i:s');

    // Query pentru a insera comentariul în baza de date
    $sql = "INSERT INTO comments (calendar_id, user_id, comment, created_at) VALUES ('$calendarId', '$userId', '$comment')";

    if (mysqli_query($conn, $sql)) {
        echo "Comentariu adăugat cu succes!";
    } else {
        echo "Eroare la adăugarea comentariului: " . mysqli_error($conn);
    }
} else {
    echo "Date insuficiente pentru adăugarea comentariului.";
}

// Închide conexiunea la baza de date la finalul scriptului
mysqli_close($conn);
?>
