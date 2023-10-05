<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}

// Database connection
$servername = "localhost"; 
$username = "Edu"; 
$password = "Eduard123"; 
$database = "edu"; 

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
function addEvent($conn, $title, $description, $start_date, $end_date, $location, $participants, $additionalFields) {
    // Convert additional fields into JSON format and store in the 'additional_fields' column
    $additionalFieldsJSON = json_encode($additionalFields);
    
    $sql = "INSERT INTO events (title, description, start_date, end_date, location, participants, additional_fields) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $title, $description, $start_date, $end_date, $location, $participants, $additionalFieldsJSON);
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

// Function to update an event in the database
function updateEvent($conn, $event_id, $title, $description, $start_date, $end_date, $location, $participants) {
    $sql = "UPDATE events SET title=?, description=?, start_date=?, end_date=?, location=?, participants=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $title, $description, $start_date, $end_date, $location, $participants, $event_id);
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

        $start_date_additional = isset($_POST["start_date_additional"]) ? $_POST["start_date_additional"] : '';
        $end_date_additional = isset($_POST["end_date_additional"]) ? $_POST["end_date_additional"] : '';
        $location_additional = isset($_POST["location_additional"]) ? $_POST["location_additional"] : '';
        $participants_additional = isset($_POST["participants_additional"]) ? $_POST["participants_additional"] : '';

        $additionalFields = [
            'start_date' => $start_date_additional,
            'end_date' => $end_date_additional,
            'location' => $location_additional,
            'participants' => $participants_additional
        ];
            
        addEvent($conn, $title, $description, $start_date, $end_date, $location, $participants, $additionalFields);
    } elseif (isset($_POST["delete_event"])) {
        $event_id = $_POST["event_id"];
        deleteEvent($conn, $event_id);
    } elseif (isset($_POST["edit_event"])) {
        $event_id = $_POST["event_id"];
        $edit_title = $_POST["edit_title"];
        $edit_description = $_POST["edit_description"];
        $edit_start_date = $_POST["edit_start_date"];
        $edit_end_date = $_POST["edit_end_date"];
        $edit_location = $_POST["edit_location"];
        $edit_participants = $_POST["edit_participants"];
        updateEvent($conn, $event_id, $edit_title, $edit_description, $edit_start_date, $edit_end_date, $edit_location, $edit_participants);
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
    <br>
    <button onclick="togglePopup()">Add Event</button>

    <!-- Edit Event Button and Form -->
    <div id="editEventPopup" class="popup">
    <div class="popup-content">
        <h2>Edit Event</h2>
        <form method="post" action="">
            <input type="hidden" id="edit_event_id" name="event_id">
            <label for="edit_title">Title:</label>
            <input type="text" id="edit_title" name="edit_title" required><br>
            <label for="edit_description">Description:</label>
            <textarea id="edit_description" name="edit_description"></textarea><br>
            <label for="edit_start_date">Start Date:</label>
            <input type="datetime-local" id="edit_start_date" name="edit_start_date" required><br>
            <label for="edit_end_date">End Date:</label>
            <input type="datetime-local" id="edit_end_date" name="edit_end_date" required><br>
            <label for="edit_location">Location:</label>
            <input type="text" id="edit_location" name="edit_location"><br>
            <label for="edit_participants">Number of Participants:</label>
            <input type="number" id="edit_participants" name="edit_participants" required><br>
            <!-- Additional Fields Container for Edit -->
            <div id="edit_additionalFields"></div>
            <button type="submit" name="edit_event">Save</button>
            <button type="button" onclick="cancelEdit()">Cancel</button>
        </form>
    </div>
</div>

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
            <!-- Add more button -->
            <button type="button" onclick="toggleAdditionalFields()">+ Add more</button><br>

            <!-- Additional Fields Container for Add (Initially hidden) -->
            <div id="additionalFields" style="display: none;"></div>

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
                <?php
                // Decode and display additional fields if they exist
                if (!empty($event["additional_fields"])) {
                    $additionalFields = json_decode($event["additional_fields"], true);
                    foreach ($additionalFields as $fieldName => $fieldValue) {
                        if (!empty($fieldValue)) {
                            echo ucfirst($fieldName) . ": " . $fieldValue . "<br>";
                        }
                    }
                }
                ?>
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?= $event["id"] ?>">
                    <button type="button" onclick="toggleEditPopup(<?php echo htmlspecialchars(json_encode($event)); ?>)">Edit</button>
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

        // Function to toggle the visibility of the edit event form popup and populate form fields
        function toggleEditPopup(event) {
        document.getElementById("edit_event_id").value = event.id;
        document.getElementById("edit_title").value = event.title;
        document.getElementById("edit_description").value = event.description;
        document.getElementById("edit_start_date").value = event.start_date.replace(" ", "T");
        document.getElementById("edit_end_date").value = event.end_date.replace(" ", "T");
        document.getElementById("edit_location").value = event.location;
        document.getElementById("edit_participants").value = event.participants;

        // Handle additional fields for editing
        var editAdditionalFields = document.getElementById("edit_additionalFields");
        editAdditionalFields.innerHTML = ''; // Clear existing fields

        var additionalFields = JSON.parse(event.additional_fields);
        for (var key in additionalFields) {
            if (additionalFields.hasOwnProperty(key)) {
                var label = document.createElement("label");
                label.textContent = key.charAt(0).toUpperCase() + key.slice(1) + ":";

                var input = document.createElement("input");
                input.type = "text";
                input.name = "edit_" + key;
                input.value = additionalFields[key];

                editAdditionalFields.appendChild(label);
                editAdditionalFields.appendChild(input);
            }
        }

        var popup = document.getElementById("editEventPopup");
        if (popup.style.display === "none" || popup.style.display === "") {
            popup.style.display = "block";
        } else {
            popup.style.display = "none";
        }
    }

    // Function to cancel editing and hide the edit event form
    function cancelEdit() {
        var popup = document.getElementById("editEventPopup");
        popup.style.display = "none";
    }
    function toggleAdditionalFields() {
        var additionalFields = document.getElementById("additionalFields");
        if (additionalFields.style.display === "none" || additionalFields.style.display === "") {
            additionalFields.style.display = "block";

            // Add labels for additional fields
            additionalFields.innerHTML = `
                <label for="start_date_additional">Start Date:</label>
                <input type="datetime-local" name="start_date_additional"><br>
                <label for="end_date_additional">End Date:</label>
                <input type="datetime-local" name="end_date_additional"><br>
                <label for="location_additional">Location:</label>
                <input type="text" name="location_additional"><br>
                <label for="participants_additional">Number of Participants:</label>
                <input type="number" name="participants_additional"><br>
            `;
        } else {
            additionalFields.style.display = "none";
            additionalFields.innerHTML = ""; // Clear the additional fields
        }
    }
    // Function to dynamically add more fields for start date, end date, location, and participants
    function addMoreFields() {
        var container = document.getElementById("additionalFields");
        var newFields = document.createElement("div");

        newFields.innerHTML = `
            <label for="start_date_additional">Additional Start Date:</label>
            <input type="datetime-local" name="start_date_additional"><br>
            <label for="end_date_additional">Additional End Date:</label>
            <input type="datetime-local" name="end_date_additional"><br>
            <label for="location_additional">Additional Location:</label>
            <input type="text" name="location_additional"><br>
            <label for="participants_additional">Additional Number of Participants:</label>
            <input type="number" name="participants_additional"><br>
        `;

        container.appendChild(newFields);
    }
    </script>
</body>
</html>
