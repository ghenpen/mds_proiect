<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    include 'db.php';

    $friend_username = mysqli_real_escape_string($conn, $_POST['friend_username']);
    $username = $_SESSION['username'];

    if ($friend_username == $username) {
        $error_message = "You cannot send a friend request to yourself.";
    } else {
        $query = "SELECT * FROM user WHERE username='$friend_username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // Get user IDs
            $friend_row = mysqli_fetch_assoc($result);
            $friend_id = $friend_row['id'];

            $user_result = mysqli_query($conn, "SELECT id FROM user WHERE username='$username'");
            $user_row = mysqli_fetch_assoc($user_result);
            $user_id = $user_row['id'];

            // Check if the friendship already exists
            $check_friendship = "SELECT * FROM friendship WHERE (userId1='$user_id' AND userId2='$friend_id') OR (userId1='$friend_id' AND userId2='$user_id')";
            $friendship_result = mysqli_query($conn, $check_friendship);
            if (mysqli_num_rows($friendship_result) == 0) {
                // Check if the request already exists
                $check_request = "SELECT * FROM friend_requests WHERE sender='$username' AND receiver='$friend_username'";
                $request_result = mysqli_query($conn, $check_request);
                if (mysqli_num_rows($request_result) == 0) {
                    // Send friend request
                    $query = "INSERT INTO friend_requests (sender, receiver) VALUES ('$username', '$friend_username')";
                    if (mysqli_query($conn, $query)) {
                        $message = "Friend request sent!";
                        echo '<script>alert("' . $message . '"); window.location.href = "friends.php";</script>';
                        exit(); // Ensure script stops here after redirection
                    } else {
                        $error_message = "Error: could not send friend request.";
                    }
                } else {
                    $error_message = "Friend request already sent.";
                }
            } else {
                $error_message = "You are already friends.";
            }
        } else {
            $error_message = "User not found.";
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Friend</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: grid;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container input {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <?php
    include 'header.php';
    ?>

    <div class="form-container">
        <?php
        if (isset($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
        <form method="post">
            <label for="friend_username">Username Friend:</label>
            <input type="text" id="friend_username" name="friend_username" required>
            <button type="submit">Add Friend</button>
        </form>
    </div>
</body>

</html>