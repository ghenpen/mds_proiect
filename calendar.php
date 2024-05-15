<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calendar</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body>
        <?php
        session_start();
        // Conectarea la baza de date
        $conn = mysqli_connect("localhost", "root", "", "proiect_mds");

        if($conn === false){
            die("Eroare la conectare. " . mysqli_connect_error());
        }

        $user_id = $_SESSION['id'];
        $user_name = $_SESSION['username'];


        // Verificăm dacă există un parametru "calendar_id" în URL
        include 'header.php';

        if(isset($_GET['calendar_id'])) {
            // Accesăm valoarea parametrului "calendar_id"
            $calendar_id = $_GET['calendar_id'];
            $name = "SELECT name FROM calendar WHERE id = $calendar_id"; 
            $result = mysqli_query($conn, $name);
            $row = mysqli_fetch_assoc($result);
        } else {
            // Dacă nu există parametrul "calendar_id" în URL, afișăm un mesaj de eroare sau facem o altă acțiune
            echo "Nu ați specificat un ID de calendar.";
        }
        ?>
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
                    <button id="previous"
                            onclick="previous()">
                    </button>
                    <button id="next"
                            onclick="next()">
                    </button>
                </div>
                <table class="table-calendar"
                        id="calendar"
                        data-lang="en">
                    <thead id="thead-month"></thead>
                    <!-- Table body for displaying the calendar -->
                    <tbody id="calendar-body"></tbody>
                </table>
                <div class="footer-container-calendar">
                    <label for="month">Jump To: </label>
                    <!-- Dropdowns to select a specific month and year -->
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
                    <!-- Dropdown to select a specific year -->
                    <select id="year" onchange="jump()"></select>
                </div>
            </div>
        </div>
        <div class="legend">
            <h3>Legenda:</h3>
            <?php
            // Conectare la baza de date și alte operații necesare
            $conn = mysqli_connect("localhost", "root", "", "proiect_mds");

            if($conn === false){
                die("Eroare la conectare. " . mysqli_connect_error());
            }
            // Interogare SQL pentru a extrage descrierea și culoarea evenimentelor
            $event_query = "SELECT description, type FROM event WHERE calendarId = $calendar_id";
            $event_result = mysqli_query($conn, $event_query);

            // Verifică dacă sunt evenimente în baza de date
            if(mysqli_num_rows($event_result) > 0) {
                // Există evenimente, afișează legenda
                echo "<ul>";
                // Iterează prin fiecare rând din rezultatul interogării
                while($row = mysqli_fetch_assoc($event_result)) {
                    // Afișează descrierea și culoarea fiecărui eveniment ca o intrare în legenda
                    echo "<li><div class='event-color' style='width:20px; height:20px; background-color:".$row['type']."'></div>".$row['description']."</li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                // Nu sunt evenimente în baza de date, afișează un mesaj alternativ sau iei o altă acțiune
                echo "Nu există evenimente de afișat în legendă.";
            }
            ?>
        </div>
    </div>
    <?php
    // Verifică dacă formularul a fost trimis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conectează-te la baza de date
        $conn = mysqli_connect("localhost", "root", "", "proiect_mds");

        if($conn === false){
            die("Eroare la conectare. " . mysqli_connect_error());
        }

        // Extrage datele din formular
        $eventDate = $_POST['eventDate'];
        $eventTime = $_POST['eventTime'];
        $eventLocation = $_POST['eventLocation'];
        $eventDescription = $_POST['eventDescription'];
        $eventColor = $_POST['eventColor'];

        $query = "SELECT * FROM event WHERE type = '$eventColor' and calendarId = '$calendar_id'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
            echo "<script>alert('Evenimentul nu poate fi adăugat. Alegeți o altă culoare.');</script>";
        }
        else{
        // Inserează datele în baza de date
            $sql = "INSERT INTO event (calendarId, date, time, location, description, type) 
                    VALUES ('$calendar_id','$eventDate', '$eventTime', '$eventLocation', '$eventDescription', '$eventColor')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Eveniment adăugat cu succes!');</script>";
            } else {
                echo "Eroare: " . $sql . "<br>" . mysqli_error($conn);
            }

            // Închide conexiunea la baza de date
            
        }
        $query = "SELECT date,type FROM event WHERE calendarId = '$calendar_id'"; 
        $result = mysqli_query($conn, $query);
        $events = array();
        while($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;

    }

    // Convertim array-ul PHP în format JSON
    $eventsJson = json_encode($events);
    mysqli_close($conn);
    }
    ?>
<script>
let eventsphp = <?php echo $eventsJson; ?>;
console.log(eventsphp);
// Define an array to store events
let events = [];
// Counter to generate unique event IDs
let eventIdCounter = 1;

eventsphp.forEach(event => {
    let date = event.date;
    let type = event.type;
    if (date && type) {
        // Adăugarea evenimentului în array
        let eventId = eventIdCounter++;
        events.push({
            id: eventId,
            date: date,
            type: type
        });
    }
});

// Function to delete an event by ID
function deleteEvent(eventId) {
	// Find the index of the event with the given ID
	let eventIndex =
		events.findIndex((event) =>
			event.id === eventId);

	if (eventIndex !== -1) {
		// Remove the event from the events array
		events.splice(eventIndex, 1);
		showCalendar(currentMonth, currentYear);
		displayReminders();
	}
}

// Function to generate a range of 
// years for the year select input
function generate_year_range(start, end) {
	let years = "";
	for (let year = start; year <= end; year++) {
		years += "<option value='" +
			year + "'>" + year + "</option>";
	}
	return years;
}

// Initialize date-related letiables
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

// Function to navigate to the next month
function next() {
	currentYear = currentMonth === 11 ?
		currentYear + 1 : currentYear;
	currentMonth = (currentMonth + 1) % 12;
	showCalendar(currentMonth, currentYear);
}

// Function to navigate to the previous month
function previous() {
	currentYear = currentMonth === 0 ?
		currentYear - 1 : currentYear;
	currentMonth = currentMonth === 0 ?
		11 : currentMonth - 1;
	showCalendar(currentMonth, currentYear);
}

// Function to jump to a specific month and year
function jump() {
	currentYear = parseInt(selectYear.value);
	currentMonth = parseInt(selectMonth.value);
	showCalendar(currentMonth, currentYear);
}

// Function to display the calendar
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
				cell.innerHTML = "<span>" + date + "</span";

				if (
					date === today.getDate() &&
					year === today.getFullYear() &&
					month === today.getMonth()
				) {
					cell.className = "date-picker selected";
				}

				// Check if there are events on this date
				if (hasEventOnDate(date, month, year)) {
					let eventsOnThisDate = getEventsOnDate(date, month, year);
                eventsOnThisDate.forEach(event => {
                    cell.style.backgroundColor = event.type; // Setează culoarea de fundal a celulei
                });
				}

				row.appendChild(cell);
				date++;
			}
		}
		tbl.appendChild(row);
	}
}

// Function to create an event tooltip
function createEventTooltip(date, month, year) {
    let tooltip = document.createElement("div");
    tooltip.className = "event-tooltip";
    let eventsOnDate = getEventsOnDate(date, month, year);
    for (let i = 0; i < eventsOnDate.length; i++) {
        let event = eventsOnDate[i];
        let eventElement = document.createElement("div");
        eventElement.style.backgroundColor = event.type; // Setează culoarea de fundal a casetei

        // Adaugă elementul evenimentului la tooltip
        tooltip.appendChild(eventElement);
    }

    return tooltip;
}


// Function to get events on a specific date
function getEventsOnDate(date, month, year) {
	return events.filter(function (event) {
		let eventDate = new Date(event.date);
		return (
			eventDate.getDate() === date &&
			eventDate.getMonth() === month &&
			eventDate.getFullYear() === year
		);
	});
}

// Function to check if there are events on a specific date
function hasEventOnDate(date, month, year) {
	return getEventsOnDate(date, month, year).length > 0;
}

// Function to get the number of days in a month
function daysInMonth(iMonth, iYear) {
	return 32 - new Date(iYear, iMonth, 32).getDate();
}

// Call the showCalendar function initially to display the calendar
showCalendar(currentMonth, currentYear);
</script>
</body>
</html>