<?php
// Database connection
$servername = "localhost"; // Change to your database server name
$username = "Edu"; // Change to your database username
$password = "Eduard123"; // Change to your database password
$database = "edu"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve events from the database
function getEvents($conn) {
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    $events = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }

    return $events;
}

// Function to add a new event to the database
function addEvent($conn, $title, $description, $start_date, $end_date, $location, $participants) {
    $sql = "INSERT INTO events (title, description, start_date, end_date, location, participants) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $description, $start_date, $end_date, $location, $participants);
    $stmt->execute();
    $stmt->close();
 }
 

// Function to delete an event from the database
function deleteEvent($conn, $event_id) {
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_event"])) {
        $title = $_POST["title"];
        $description = $_POST["description"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        $location = $_POST["location"];
        $participants = $_POST["participants"];
        addEvent($conn, $title, $description, $start_date, $end_date, $location, $participants);     
    } elseif (isset($_POST["delete_event"])) {
        $event_id = $_POST["event_id"];
        deleteEvent($conn, $event_id);
    }
}

// Get the list of events
$events = getEvents($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tapahtumat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
    <title>Event Manager</title>
    <style>
        /* Styles for the form popup */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
        <!-- Logo -->
        <a class="navbar-brand"><img src="../images/turku.png" alt="Logo" width="40"> </a>
        
        <!-- Navbar toggler button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="./tapahtumat.php">Tapahtumat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Osallistujat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Käyttäjät</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Asetukset</a>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    <!-- Add Event Button -->
    <button onclick="togglePopup()">Add Event</button>

    <!-- Form Popup -->
    <div id="addEventPopup" class="popup">
        <div class="popup-content">
            <h2>Add Event</h2>
            <form method="post" action="">
                <label for="title">Title:</label>
                <input type="text" name="title" required><br>
                <label for="description">Description:</label>
                <textarea name="description"></textarea><br>
                <label for="start_date">Start Date:</label>
                <input type="datetime-local" name="start_date" required><br>
                <label for="end_date">End Date:</label>
                <input type="datetime-local" name="end_date" required><br>
                <label for="location">Location:</label>
                <input type="text" name="location"><br>
                <label for="participants">Number of Participants:</label>
                <input type="number" name="participants" required><br>
                <button type="submit" name="add_event">Add Event</button>
                <button type="button" onclick="togglePopup()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- List of Events -->
    <ul>
            <?php foreach ($events as $event) { ?>
                <li>
        <strong><?= $event["title"] ?></strong><br>
        Description: <?= $event["description"] ?><br>
        Start Date: <?= $event["start_date"] ?><br>
        End Date: <?= $event["end_date"] ?><br>
        Location: <?= $event["location"] ?><br>
        Participants: <?= $event["participants"] ?><br>
        <form method="post" action="">
            <input type="hidden" name="event_id" value="<?= $event["id"] ?>">
    
            <button type="submit" name="delete_event">Delete</button>
        </form>
    </li>

        <?php } ?>
    </ul>

    <script>
        // Function to toggle the visibility of the add event form popup
        function togglePopup() {
            var popup = document.getElementById("addEventPopup");
            if (popup.style.display === "none" || popup.style.display === "") {
                popup.style.display = "block";
            } else {
                popup.style.display = "none";
            }
        }
    </script>
</body>
</html>
