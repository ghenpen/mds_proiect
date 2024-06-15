<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legend</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="legend">
        <h3>Legenda:</h3>
        <?php
        include 'db.php';
        if (isset($_GET['calendar_id'])) {
            $calendar_id = $_GET['calendar_id'];
            $event_query = "SELECT description, type FROM event WHERE calendarId = $calendar_id";
            $event_result = mysqli_query($conn, $event_query);

            if (mysqli_num_rows($event_result) > 0) {
                echo "<ul>";
                while ($row = mysqli_fetch_assoc($event_result)) {
                    echo "<li><div class='event-color' style='background-color:" . $row['type'] . "'></div>" . $row['description'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "Nu există evenimente de afișat în legendă.";
            }
        } else {
            echo "Nu ați specificat un ID de calendar.";
        }
        ?>
    </div>
</body>
</html>
