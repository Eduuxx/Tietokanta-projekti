<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    header("location: login.php");
    exit;
}

$servername = "localhost";
$username = "Edu";
$password = "Eduard123";
$database = "edu";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Query the users table to fetch "username" and "email"
$sqlUsers = "SELECT username, email FROM users";
$resultUsers = $conn->query($sqlUsers);

if (!$resultUsers) {
    die("Error executing the query for users: " . $conn->error);
}

// Query the settings table to fetch "title," "country," and "city"
$sqlSettings = "SELECT name, type FROM settings WHERE type IN ('title', 'country', 'city')";
$resultSettings = $conn->query($sqlSettings);

if (!$resultSettings) {
    die("Error executing the query for settings: " . $conn->error);
}
$settingsData = array(); // Store settings data in an array
while ($row = $resultSettings->fetch_assoc()) {
    $settingsData[$row['type']][] = $row['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/userpanel.css">
    <script src="../js/userscript.js"></script>
    <link rel="stylesheet" href="./css/asetukset.css">
    <script src="../js/asetukset.js"></script>
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


<!-- Add an Edit User Information Form -->
<div class="modal fade" id="editUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="editUserInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserInfoModalLabel">Vaihda käyttäjätiedot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="update_user_info.php"> <!-- Create a separate PHP file for updating user information -->
                    <div class="mb-3">
                        <label for="currentUsername" class="form-label">Käyttäjänimi</label>
                        <input type="text" class="form-control" id="currentUsername" name="currentUsername" value="<?= htmlspecialchars($_SESSION["username"]); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newUsername" class="form-label">Uusi käyttäjänimi*</label>
                        <input type="text" class="form-control" id="newUsername" name="newUsername" required>
                    </div>
                    <!-- Email section (read-only) -->
                    <div class="mb-3">
                        <label for="currentEmail" class="form-label">Sähköposti</label>
                        <input type="email" class="form-control" id="currentEmail" name="currentEmail" value="<?= isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"]) : ''; ?>" readonly>
                    </div>
                    <!-- End of Email section -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Titteli</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Osasto</label>
                        <input type="text" class="form-control" id="country" name="country">
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Työpaikka</label>
                        <input type="text" class="form-control" id="city" name="city">
                    </div>
                    <button type="submit" class="btn btn-primary">Tallenna</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <table class="table">
        <thead>
            <tr>
                <th>Käyttäjänimi</th>
                <th>Sähköposti</th>
                <?php if (isset($settingsData['title'])): ?>
                    <th>Titteli</th>
                <?php endif; ?>
                <?php if (isset($settingsData['country'])): ?>
                    <th>Osasto</th>
                <?php endif; ?>
                <?php if (isset($settingsData['city'])): ?>
                    <th>Työpaikka</th>
                <?php endif; ?>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the fetched user data and display it in the table
            if ($resultUsers->num_rows > 0) {
                while ($row = $resultUsers->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    if (isset($settingsData['title'])) {
                        echo "<td>" . implode(', ', $settingsData['title']) . "</td>";
                    }
                    if (isset($settingsData['country'])) {
                        echo "<td>" . implode(', ', $settingsData['country']) . "</td>";
                    }
                    if (isset($settingsData['city'])) {
                        echo "<td>" . implode(', ', $settingsData['city']) . "</td>";
                    }
                    echo "<td><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editUserInfoModal'>Edit</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
