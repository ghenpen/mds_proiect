<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .cod{
            flex: 0 0 30%;
            padding-left: 20px;
        }
        .event-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 2px;
            display: inline-block;
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include 'db.php';

    $user_id = $_SESSION['id'];
    $user_name = $_SESSION['username'];
    $_SESSION['show_back_button'] = true;

    include 'header.php';

    if (isset($_GET['calendar_id'])) {
        $calendar_id = $_GET['calendar_id'];
        $name = "SELECT name FROM calendar WHERE id = $calendar_id";
        $result = mysqli_query($conn, $name);
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Nu ați specificat un ID de calendar.";
    }
    ?>
<div class="tot">
    <div class="wrapper">
        <div class="container-calendar">
            <div id="left">
                <h1><?php echo $row['name']; ?></h1>
                <form method="post" id="eventForm">
                    <div id="event-section">
                        <h3>Add Event</h3>
                        <label for="eventDate">Date:</label>
                        <input type="date" id="eventDate" name="eventDate" required><br>
                        <label for="eventTime">Time:</label>
                        <input type="time" id="eventTime" name="eventTime" required><br>
                        <label for="eventLocation">Location:</label>
                        <input type="text" id="eventLocation" name="eventLocation" placeholder="Event Location" required><br>
                        <label for="eventTitle">Title:</label>
                        <input type="text" id="eventTitle" name="eventTitle" placeholder="Event Title" required><br>
                        <label for="eventDescription">Description:</label>
                        <input type="text" id="eventDescription" name="eventDescription" placeholder="Event Description"><br>
                        <label for="eventColor">Color:</label>
                        <input type="color" id="eventColor" name="eventColor" required><br>
                        <button type="submit" id="addEvent">Add</button>
                    </div>
                </form>
            </div>
            <div id="right">
                <h3 id="monthAndYear"></h3>
                <div class="button-container-calendar">
                    <button id="previous" onclick="previous()">&#10094;</button>
                    <button id="next" onclick="next()">&#10095;</button>
                </div>
                <table class="table-calendar" id="calendar" data-lang="en">
                    <thead id="thead-month"></thead>
                    <tbody id="calendar-body"></tbody>
                </table>
                <div class="footer-container-calendar">
                    <label for="month">Jump To: </label>
                    <select id="month" onchange="jump()">
                        <option value=0>Jan</option>
                        <option value=1>Feb</option>
                        <option value=2>Mar</option>
                        <option value=3>Apr</option>
                        <option value=4>May</option>
                        <option value=5>Jun</option>
                        <option value=6>Jul</option>
                        <option value=7>Aug</option>
                        <option value=8>Sep</option>
                        <option value=9>Oct</option>
                        <option value=10>Nov</option>
                        <option value=11>Dec</option>
                    </select>
                    <select id="year" onchange="jump()"></select>
                </div>
            </div>
        </div>
    </div>
        <div class="cod">
            <?php 
                include 'db.php';

                $event_query = "SELECT code FROM calendar WHERE id = $calendar_id";
                $event_result = mysqli_query($conn, $event_query);

                if(mysqli_num_rows($event_result) > 0) {
                    $row = mysqli_fetch_assoc($event_result);
                    $code = $row['code'];
                ?>
            <div>
                <label for="calendarCode">Codul calendarului:</label>
                <input type="text" id="calendarCode" value="<?php echo htmlspecialchars($code); ?>" readonly>
                <button onclick="copyToClipboard()">Copy</button>
            </div>
            <script>
                function copyToClipboard() {
                    var copyText = document.getElementById("calendarCode");
                    copyText.select();
                    copyText.setSelectionRange(0, 99999); // Pentru dispozitive mobile
                    document.execCommand("copy");
                    alert("Codul a fost copiat: " + copyText.value);
                }
            </script>
            <?php
                } else {
                echo "Codul nu a fost găsit.";
                }
            ?>
        
            <div class="legend">
                <iframe src="legend.php?calendar_id=<?php echo $calendar_id; ?>" width="350" height="500"></iframe>
            </div>
        </div>
</div>
    <div id="eventPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Events on <span id="popupDate"></span></h2>
            <ul id="eventList"></ul>
            <div id="availabilitySection" style="display: none;">
                <h3>Join Event</h3>
                <label for="availabilityStatus">Availability:</label>
                <select id="availabilityStatus" name="availabilityStatus" required>
                    <option value="available">Available</option>
                    <option value="not_available">Not Available</option>
                    <option value="not_sure">Not Sure</option>
                </select><br>
                <button id="joinButton">Submit</button>
            </div>
        </div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $eventDate = $_POST['eventDate'];
        $eventTime = $_POST['eventTime'];
        $eventLocation = $_POST['eventLocation'];
        $eventDescription = $_POST['eventDescription'];
        $eventColor = $_POST['eventColor'];

        $query = "SELECT * FROM event WHERE type = '$eventColor' and calendarId = '$calendar_id'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Evenimentul nu poate fi adăugat. Alegeți o altă culoare.');</script>";
        } else {
            $sql = "INSERT INTO event (calendarId, date, time, location, description, type) 
                    VALUES ('$calendar_id','$eventDate', '$eventTime', '$eventLocation', '$eventDescription', '$eventColor')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Eveniment adăugat cu succes!');</script>";
            } else {
                echo "Eroare: " . $sql . "<br>" . mysqli_error($conn);
            }
            
        }
    }
    $query = "SELECT id , date, time, type, description FROM event WHERE calendarId = '$calendar_id'";
    $result = mysqli_query($conn, $query);
    $events = array();
    $users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    $userquery="SELECT e.id , e.date, e.time, u.username, ue.displonibility FROM event e join userinevent ue ON e.id = ue.eventId join user u ON u.id = ue.userId WHERE calendarId ='$calendar_id'";
    $userresult = mysqli_query($conn, $userquery);
    while ($row = mysqli_fetch_assoc($userresult)) {
        $users[] = $row;
    }
    $usersJson = json_encode($users);
    $eventsJson = json_encode($events);
    mysqli_close($conn);
    ?>
    <script>
        let eventsphp = <?php echo $eventsJson; ?>;
        let usersphp = <?php echo $usersJson; ?>;
        console.log(usersphp);
        let events = [];

        eventsphp.forEach(event => {
            let eventId = event.id;
            let date = event.date;
            let time = event.time;
            let type = event.type;
            let description = event.description;
            if (date && type) {
                events.push({
                    id: eventId,
                    date: date,
                    time: time,
                    type: type,
                    description: description
                });
            }
        });

        let availability = {};

        function deleteEvent(eventId) {
            let eventIndex =
                events.findIndex((event) =>
                    event.id === eventId);

            if (eventIndex !== -1) {
                events.splice(eventIndex, 1);
                showCalendar(currentMonth, currentYear);
                displayReminders();
            }
        }

        function generate_year_range(start, end) {
            let years = "";
            for (let year = start; year <= end; year++) {
                years += "<option value='" +
                    year + "'>" + year + "</option>";
            }
            return years;
        }

        today = new Date();
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        selectYear = document.getElementById("year");
        selectMonth = document.getElementById("month");

        createYear = generate_year_range(1970, 2050);

        document.getElementById("year").innerHTML = createYear;

        let calendar = document.getElementById("calendar");

        let months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];
        let days = [
            "Sun", "Mon", "Tue", "Wed",
            "Thu", "Fri", "Sat"];

        $dataHead = "<tr>";
        for (dhead in days) {
            $dataHead += "<th data-days='" +
                days[dhead] + "'>" +
                days[dhead] + "</th>";
        }
        $dataHead += "</tr>";

        document.getElementById("thead-month").innerHTML = $dataHead;

        monthAndYear =
            document.getElementById("monthAndYear");
        showCalendar(currentMonth, currentYear);

        function next() {
            currentYear = currentMonth === 11 ?
                currentYear + 1 : currentYear;
            currentMonth = (currentMonth + 1) % 12;
            showCalendar(currentMonth, currentYear);
        }

        function previous() {
            currentYear = currentMonth === 0 ?
                currentYear - 1 : currentYear;
            currentMonth = currentMonth === 0 ?
                11 : currentMonth - 1;
            showCalendar(currentMonth, currentYear);
        }

        function jump() {
            currentYear = parseInt(selectYear.value);
            currentMonth = parseInt(selectMonth.value);
            showCalendar(currentMonth, currentYear);
        }

        function showCalendar(month, year) {
            let firstDay = new Date(year, month, 1).getDay();
            tbl = document.getElementById("calendar-body");
            tbl.innerHTML = "";
            monthAndYear.innerHTML = months[month] + " " + year;
            selectYear.value = year;
            selectMonth.value = month;
            let date = 1;
            for (let i = 0; i < 6; i++) {
                let row = document.createElement("tr");
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        cell = document.createElement("td");
                        cellText = document.createTextNode("");
                        cell.appendChild(cellText);
                        row.appendChild(cell);
                    } else if (date > daysInMonth(month, year)) {
                        break;
                    } else {
                        cell = document.createElement("td");
                        cell.setAttribute("data-date", date);
                        cell.setAttribute("data-month", month + 1);
                        cell.setAttribute("data-year", year);
                        cell.setAttribute("data-month_name", months[month]);
                        cell.className = "date-picker";
                        cell.innerHTML = "<span>" + date + "</span>";

                        if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                            cell.className = "date-picker selected";
                        }

                        if (hasEventOnDate(date, month, year)) {
                            let eventsOnThisDate = getEventsOnDate(date, month, year);
                            eventsOnThisDate.forEach(event => {
                                let eventIndicator = document.createElement("div");
                                eventIndicator.className = "event-indicator";
                                eventIndicator.style.backgroundColor = event.type;
                                cell.appendChild(eventIndicator);
                            });
                        }

                        cell.addEventListener("click", function () {
                            let date = this.getAttribute("data-date");
                            let month = this.getAttribute("data-month") - 1;
                            let year = this.getAttribute("data-year");
                            showPopup(date, month, year);
                        });

                        row.appendChild(cell);
                        date++;
                    }
                }
                tbl.appendChild(row);
            }
        }

        function createEventTooltip(date, month, year) {
            let tooltip = document.createElement("div");
            tooltip.className = "event-tooltip";
            let eventsOnDate = getEventsOnDate(date, month, year);
            for (let i = 0; i < eventsOnDate.length; i++) {
                let event = eventsOnDate[i];
                let eventElement = document.createElement("div");
                eventElement.style.backgroundColor = event.type;

                tooltip.appendChild(eventElement);
            }

            return tooltip;
        }

        function getEventsOnDate(date, month, year) {
            return events.filter(function (event) {
                let eventDate = new Date(event.date);
                return (
                    eventDate.getDate() === parseInt(date) &&
                    eventDate.getMonth() === parseInt(month) &&
                    eventDate.getFullYear() === parseInt(year)
                );
            });
        }

        function hasEventOnDate(date, month, year) {
            return getEventsOnDate(date, month, year).length > 0;
        }

        function daysInMonth(iMonth, iYear) {
            return 32 - new Date(iYear, iMonth, 32).getDate();
        }

        function showPopup(date, month, year) {
    let eventsOnDate = getEventsOnDate(date, month, year);
    eventsOnDate.sort((a, b) => new Date(`1970-01-01T${a.time}`) - new Date(`1970-01-01T${b.time}`));

    let eventList = document.getElementById("eventList");
    eventList.innerHTML = "";

    eventsOnDate.forEach(event => {
        let listItem = document.createElement("li");
        listItem.innerHTML = `${event.time} - ${event.description}`;
        
        // Afișează utilizatorii și disponibilitatea acestora
        let usersForEvent = usersphp.filter(user => user.id === event.id);
        console.log(usersForEvent);
        if (usersForEvent.length > 0) {
            let userList = document.createElement("ul");
            usersForEvent.forEach(user => {
                let userItem = document.createElement("li");
                userItem.innerHTML = `${user.username}: ${user.displonibility}`;
                userList.appendChild(userItem);
            });
            listItem.appendChild(userList);
        } else {
            let noUsers = document.createElement("p");
            noUsers.innerHTML = "No users available.";
            listItem.appendChild(noUsers);
        }
        // Adaugă un buton "Join" pentru a seta disponibilitatea
        let joinButton = document.createElement("button");
        joinButton.innerHTML = "Join";
        joinButton.onclick = function() {
            let availabilityStatus = prompt("Enter your availability (available, not_available, not_sure):");
            if (availabilityStatus) {
                sendAvailability(event.id, availabilityStatus);
            }
        };
        listItem.appendChild(joinButton);
        eventList.appendChild(listItem);
    });

    document.getElementById("popupDate").textContent = `${date} ${months[month]} ${year}`;
    document.getElementById("eventPopup").style.display = "block";
}

        function closePopup() {
            document.getElementById("eventPopup").style.display = "none";
        }
        function showAvailabilityForm(eventId, eventTime) {
    document.getElementById("availabilitySection").style.display = "block";
    document.getElementById("eventList").style.display = "none";
    document.getElementById("popupDate").style.display = "none";
    window.currentEventId = eventId;
}

function sendAvailability(eventId, availabilityStatus) {
    let userId = <?php echo $user_id;?>;

    console.log("Sending data:", userId, eventId, availabilityStatus);

    $.ajax({
        type: 'POST',
        url: 'submit_availability.php',
        data: {
            userId: userId,
            eventId: eventId,
            availabilityStatus: availabilityStatus
        },
        success: function(response) {
            console.log("Response from PHP:", response);
            alert(response); // Afișează răspunsul de la PHP
        },
        error: function(xhr, status, error) {
            console.error("Error:", xhr.status, error);
            alert("Error submitting availability. Please try again.");
        }
    });
}

// Modify the join button's onclick function in showPopup
joinButton.onclick = function() {
    let eventId = event.id;
    let availabilityStatus = prompt("Enter your availability (available, not_available, not_sure):");
    if (availabilityStatus) {
        sendAvailability(eventId, availabilityStatus);
    }
};


        showCalendar(currentMonth, currentYear);
    </script>
</body>
</html>