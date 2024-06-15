<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legend</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .event-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        .search-bar {
            width: 200px; /* Adjust as needed to fit within iframe */
            padding: 10px;
            margin: 10px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .legend ul {
            padding: 0;
            list-style-type: none;
        }
        .legend li {
            padding: 5px 0;
        }
        .event-details {
            display: none;
        }
    </style>
</head>
<body>
    <div class="legend">
        <h3>Legenda:</h3>
        <input type="text" id="searchBar" class="search-bar" onkeyup="filterEvents()" placeholder="Căutați evenimente...">
        <br>
        <?php
        include 'db.php';
        if (isset($_GET['calendar_id'])) {
            $calendar_id = $_GET['calendar_id'];
            // Fetch all events ordered by date and time
            $event_query = "SELECT title, description, location, type FROM event WHERE calendarId = $calendar_id ORDER BY date ASC, time ASC";
            $event_result = mysqli_query($conn, $event_query);

            if (mysqli_num_rows($event_result) > 0) {
                echo "<ul id='eventList'>";
                while ($row = mysqli_fetch_assoc($event_result)) {
                    echo "<li><div class='event-color' style='background-color:" . htmlspecialchars($row['type']) . "'></div><span class='event-title'>" . htmlspecialchars($row['title']) . "</span><span class='event-details'>" . htmlspecialchars($row['description']) . " " . htmlspecialchars($row['location']) . "</span></li>";
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
    <script>
        function filterEvents() {
            var input, filter, ul, li, title, details, i, txtValue, detailsValue;
            input = document.getElementById('searchBar');
            filter = input.value.toUpperCase();
            ul = document.getElementById("eventList");
            li = ul.getElementsByTagName('li');

            for (i = 0; i < li.length; i++) {
                title = li[i].getElementsByClassName("event-title")[0];
                details = li[i].getElementsByClassName("event-details")[0];
                txtValue = title.textContent || title.innerText;
                detailsValue = details.textContent || details.innerText;
                if (filter === "" || txtValue.toUpperCase().indexOf(filter) > -1 || detailsValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
