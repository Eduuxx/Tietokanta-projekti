<?php
# Initialize the session
session_start();

# If the user is not logged in, redirect them to the login page
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
function getEvents($conn)
{
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
function addEvent($conn, $title, $description, $start_date, $end_date, $location, $participants, $additionalFields)
{
    // Convert additional fields into JSON format and store in the 'additional_fields' column
    $additionalFieldsJSON = json_encode($additionalFields);

    $sql = "INSERT INTO events (title, description, start_date, end_date, location, participants, additional_fields) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $title, $description, $start_date, $end_date, $location, $participants, $additionalFieldsJSON);
    $stmt->execute();
    $stmt->close();
}

// Function to delete an event from the database
function deleteEvent($conn, $event_id)
{
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->close();
}


// Function to update an event in the database
function updateEvent($conn, $event_id, $title, $description, $start_date, $end_date, $location, $participants)
{
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
    <link rel="stylesheet" href="./css/userpanel.css">
    <link rel="stylesheet" href="./css/tapahtumat.css">
    <script src="../js/userscript.js"></script>
    <script src="../js/tapahtumat.js"></script>
    <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
</head>
<body>
<!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand"><img src="../images/turku.png" alt="Logo" width="40"></a>

                <!-- Navbar toggler button for mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                            <a class="nav-link" href="./asetukset.php">Asetukset</a>
                        </li>
                    </ul>
                </div>

                <!-- Circle Logo/Button -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userPanelModal">
                    <img src="../images/turku.png" alt="Logo" width="40">
                </button>
            </div>
        </nav>

        <!-- User Panel Modal -->
        <div class="modal fade" id="userPanelModal" tabindex="-1" role="dialog" aria-labelledby="userPanelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userPanelModalLabel">Käyttäjä Paneli</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center"> <!-- Center the content -->
                        <!-- User profile -->
                        <img src="../images/turku.png" class="img-fluid rounded" alt="User avatar" width="180">
                        <h4 class="my-4">@<?= htmlspecialchars($_SESSION["username"]); ?></h4>
                        <p><?= isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"]) : ''; ?></p>
                        <a href="./logout.php" class="btn btn-primary">KIRJAUDU ULOS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


    <!-- Add Event Button -->
    <br>
    <button class="btn btn-primary" onclick="togglePopup()">LISÄÄ TAPAHTUMA</button>

    

    <!-- Form Popup -->
    <div id="addEventPopup" class="popup">
    <div class="popup-content">
        <h2 >Lisää Tapahtuma</h2>
        <form method="post" action="">
            <!-- Title Section -->
            <div class="popup-section">
                <div class="popup-section-title">Otsikko*</div>
                <input type="text" name="title" required>
            </div>

            <!-- Description Section -->
            <div class="popup-section">
                <div class="popup-section-title">Kuvaus</div>
                <textarea name="description"></textarea>
            </div>

            <!-- Location Section -->
            <div class="popup-section">
                <div class="popup-section-title">Tapahtumapaikka*</div>
                <input type="text" name="location">
            </div>

            <!-- Date Section -->
            <div class="popup-section">
                <div class="popup-section-title">Aloituspäivämäärä ja -aika</div>
                <input type="datetime-local" name="start_date" required>
                <div class="popup-section-title">Loppumispäivämäärä ja -aika</div>
                <input type="datetime-local" name="end_date" required>
            </div>

            <!-- Participants Section -->
            <div class="popup-section">
                <div class="popup-section-title">Maksimi osallistujamäärä</div>
                <input type="number" name="participants" required>
            </div>

            <!-- Additional Fields Section -->
            <div class="popup-section">
                <button type="button" class="btn btn-primary" onclick="toggleAdditionalFields()">+ Uusi ajankohta</button>
                <div id="additionalFields" style="display: none;">
                <div class="popup-subsection">
                        <div class="popup-subsection-title">Tapahtumapaikka*</div>
                        <input type="text" name="location_additional">
                    </div>
                    <div class="popup-subsection">
                        <div class="popup-subsection-title">Aloituspäivämäärä ja -aika</div>
                        <input type="datetime-local" name="start_date_additional">
                    </div>
                    <div class="popup-subsection">
                        <div class="popup-subsection-title">Loppumispäivämäärä ja -aika</div>
                        <input type="datetime-local" name="end_date_additional">
                    </div>
                    
                    <div class="popup-subsection">
                        <div class="popup-subsection-title">Maksimi osallistujamäärä</div>
                        <input type="number" name="participants_additional">
                    </div>
                </div>
            </div>

            <!-- Buttons Section -->
            <div class="popup-section-buttons">
                <button type="submit" class="btn btn-primary" name="add_event">Lisää tapahtuma</button>
                <button type="button" class="btn btn-danger" onclick="togglePopup()">Peruuta</button>
            </div>
        </form>
    </div>
</div>
    
 <!-- Edit Event Button and Form -->
<div id="editEventPopup" class="popup">
    <div class="popup-content">
        <h2>Edit Event</h2>
        <form method="post" action="">
            <!-- Event ID Field -->
            <input type="hidden" name="event_id" id="edit_event_id">

            <!-- Title Section -->
            <div class="popup-section">
                <div class="popup-section-title">Otsikko*</div>
                <input type="text" id="edit_title" name="edit_title" required>
            </div>

            <!-- Description Section -->
            <div class="popup-section">
                <div class="popup-section-title">Kuvaus</div>
                <textarea id="edit_description" name="edit_description"></textarea>
            </div>

            <!-- Location Section -->
            <div class="popup-section">
                <div class="popup-section-title">Tapahtumapaikka</div>
                <input type="text" id="edit_location" name="edit_location">
            </div>

            <!-- Date Section -->
            <div class="popup-section">
                <div class="popup-section-title">Aloituspäivämäärä ja -aika</div>
                <input type="datetime-local" id="edit_start_date" name="edit_start_date" required>
                <div class="popup-section-title">Loppumispäivämäärä ja -aika</div>
                <input type="datetime-local" id="edit_end_date" name="edit_end_date" required>
            </div>

            <!-- Participants Section -->
            <div class="popup-section">
                <div class="popup-section-title">Maksimi osallistujamäärä</div>
                <input type="number" id="edit_participants" name="edit_participants" required>
            </div>

            <!-- Additional Fields Section -->
            <div class="popup-section">
                <div id="edit_additionalFields"></div>
            </div>

            <!-- Buttons Section -->
            <div class="popup-section-buttons">
                <button type="submit" class="btn btn-primary" name="edit_event">Tallenna</button>
                <button type="button" class="btn btn-danger" onclick="cancelEdit()">Peruuta</button>
            </div>
        </form>
    </div>
</div>

    <!-- List of Events -->
    <?php foreach ($events as $event) { ?>
        <div class="event-box">
            <div class="event-title"><?= $event["title"] ?></div>
            <div class="event-description"><?= $event["description"] ?></div>
            <br>
            <div><?= $event["start_date"] ?> - <?= $event["end_date"] ?> <?= $event["location"] ?></div>
            <div>Ilmoittautuneita: 0/<?= $event["participants"] ?></div>
            <?php
            // Decode and display additional fields if they exist
            if (!empty($event["additional_fields"])) {
                $additionalFields = json_decode($event["additional_fields"], true);
            
                // Check if any additional fields have values
                $hasAdditionalFields = false;
                foreach ($additionalFields as $fieldValue) {
                    if (!empty($fieldValue)) {
                        $hasAdditionalFields = true;
                        break;
                    }
                }
            
                // Output the additional fields if they have values
                if ($hasAdditionalFields) {
                    echo '<br>'; // Add spacing before additional fields
            
                    // Display the additional fields
                    echo '<div>';
                    if (!empty($additionalFields['start_date'])) {
                        // Format and display start date and time
                        $startDateTime = date('Y-m-d H:i', strtotime($additionalFields['start_date']));
                        echo '' . $startDateTime;
            
                        if (!empty($additionalFields['end_date'])) {
                            // Format and display end date and time
                            $endDateTime = date('Y-m-d H:i', strtotime($additionalFields['end_date']));
                            echo ' - ' . $endDateTime;
            
                            if (!empty($additionalFields['location'])) {
                                // Display location if it exists
                                echo ' ' . $additionalFields['location'];
                            }
                        }
            
                        echo '<br>';
                    }
            
                    if (!empty($additionalFields['participants'])) {
                        // Display the number of participants
                        echo 'Ilmoittautuneita: ' . $additionalFields['participants'] . '<br>';
                    }
            
                    echo '</div>';
                }
            }
            ?>

            <div class="event-buttons">
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?= $event["id"] ?>">
                    <button type="button" class="edit-button" onclick="toggleEditPopup(<?php echo htmlspecialchars(json_encode($event)); ?>)"></button>
                    <button type="submit" class="delete-button" name="delete_event"></button>
                </form>
            </div>
        </div>
    <?php } ?>
</body>
</html>
