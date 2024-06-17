<?php
session_start(); // Începe sesiunea pentru a gestiona starea de autentificare a utilizatorilor

// Verifică dacă utilizatorul nu este autentificat și îl redirecționează către pagina de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: loginh.php");
    exit();
}

include 'db.php'; // Include conexiunea la baza de date

$username = $_SESSION['username']; // Preia numele utilizatorului din sesiune
$_SESSION['show_back_button'] = false; // Setează variabila de sesiune pentru afișarea butonului de navigare înapoi (nu este utilizată în acest cod)

// Obține ID-ul utilizatorului bazat pe numele său de utilizator utilizând declarații pregătite
$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verifică dacă s-a obținut un rezultat și dacă există cel puțin un rând în rezultat
if ($result && $result->num_rows > 0) {
    $user_row = $result->fetch_assoc();
    $user_id = $user_row['id']; // Preia ID-ul utilizatorului
} else {
    die("Error retrieving user ID for username: " . htmlspecialchars($username)); // În caz de eroare, afișează un mesaj și oprește scriptul
}

// Obține lista de prieteni utilizând declarații pregătite
$stmt = $conn->prepare("
    SELECT u.username AS friend_username FROM friendship f
    JOIN user u ON (f.userId1 = u.id OR f.userId2 = u.id)
    WHERE (f.userId1 = ? OR f.userId2 = ?) AND u.username != ?
");
$stmt->bind_param("iis", $user_id, $user_id, $username);
$stmt->execute();
$friends = $stmt->get_result();

// Verifică dacă s-a obținut lista de prieteni cu succes
if (!$friends) {
    die("Error retrieving friends: " . htmlspecialchars($conn->error)); // În caz de eroare, afișează un mesaj și oprește scriptul
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Friends</title>
    <!-- Adaugă Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            padding-top: 100px; /* Ajustează această valoare dacă înălțimea antetului este diferită */
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

        #createButton,
        #addFriendButton,
        #friendRequestsButton {
            font-size: 5vw;
            top: 19vh;
            position: absolute;
            left: 3vw;
            background-color: #ffd2c6;
            color: white;
            width: 6vw;
        }

        #addFriendButton {
            top: 25vh;
        }

        #friendRequestsButton {
            top: 46vh;
        }

        .friend-list {
            list-style-type: none;
            padding: 0;
        }

        .friend-list li {
            margin: 10px 0;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            width: 200px;
            border-radius: 5px;
            border: 1px solid pink;
        }
        .firend-list li:hover{
            background-color: pink;
        }

        .friend-list a {
            text-decoration: none;
            color: #333;
            
        }

        .friend-list a:hover {
            text-decoration: underline;
            color: pink;
        }

        #noFriends {
            text-align: center;
            font-style: italic;
            color: #999;
            margin-top: 20px;
        }

        h1 {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; // Include antetul sau bara de navigare ?>
    <button class="back-button" onclick="window.location.href='homepage.php'" fdprocessedid="mmv3v">Înapoi</button>

    <div class="container">
        <h1>Friends</h1>

        <!-- Buton pentru adăugarea unui prieten -->
        <a href="add_friend.php"><button id="addFriendButton">+</button></a>
        <!-- Buton pentru gestionarea cererilor de prietenie -->
        <a href="manage_friend_requests.php" ><button id="friendRequestsButton"><img src="add-user.png" style="width:80px;height:80px;"></button></a>

        <h2>Friends List</h2>
        <?php if ($friends->num_rows > 0): ?>
            <!-- Afiseaza lista de prieteni daca exista cel putin un prieten -->
            <ul class="friend-list">
                <?php while ($row = $friends->fetch_assoc()): ?>
                    <a href="view_common.php?friend=<?php echo htmlspecialchars($row['friend_username']); ?>">
                    <li>
                        
                            <?php echo htmlspecialchars($row['friend_username']); ?>
                        
                    </li>
                    </a>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <!-- Afiseaza un mesaj daca nu exista prieteni -->
            <p id="noFriends">You have no friends yet.</p>
        <?php endif; ?>
    </div>

    <!-- Adaugă jQuery înainte de Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Adaugă Bootstrap JS pentru funcționalități suplimentare (opțional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>