<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Include database connection

$username = $_SESSION['username'];
$_SESSION['show_back_button'] = false;

// Get user ID from username
$result = mysqli_query($conn, "SELECT id FROM user WHERE username='$username'");
if ($result && mysqli_num_rows($result) > 0) {
    $user_row = mysqli_fetch_assoc($result);
    $user_id = $user_row['id'];
} else {
    die("Error retrieving user ID for username: $username");
}

// Get friends list
$friends = mysqli_query($conn, "SELECT u.username AS friend_username FROM friendship f
    JOIN user u ON (f.userId1 = u.id OR f.userId2 = u.id)
    WHERE (f.userId1 = '$user_id' OR f.userId2 = '$user_id') AND u.username != '$username'");
if (!$friends) {
    die("Error retrieving friends: " . mysqli_error($conn));
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
            padding-top: 100px; /* Adjust this value if your header height is different */
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
            top: 15vh;
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
            top: 35vh;
        }

        .friend-list {
            list-style-type: none;
            padding: 0;
        }

        .friend-list li {
            margin: 10px 0;
        }

        .friend-list a {
            text-decoration: none;
            color: #007bff;
        }

        .friend-list a:hover {
            text-decoration: underline;
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
    <?php
    include 'header.php';
    ?>
    <div class="container">
        <h1>Friends</h1>

        <a href="add_friend.php"><button id="addFriendButton">+</button></a>
        <a href="manage_friend_requests.php"><button id="friendRequestsButton">?</button></a>

        <h2>Friends List</h2>
        <?php if (mysqli_num_rows($friends) > 0): ?>
            <ul class="friend-list">
                <?php while ($row = mysqli_fetch_assoc($friends)): ?>
                    <li>
                        <a href="view_common.php?friend=<?php echo htmlspecialchars($row['friend_username']); ?>">
                            <?php echo htmlspecialchars($row['friend_username']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p id="noFriends">You have no friends yet.</p>
        <?php endif; ?>
    </div>

    <!-- Adaugă jQuery înainte de Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Adaugă Bootstrap JS pentru funcționalități suplimentare (opțional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>