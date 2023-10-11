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

// Get the currently logged-in user's username
$currentUser = $_SESSION["username"];

// Query the users table to fetch data for the currently logged-in user
$sqlUsers = "SELECT username, email FROM users WHERE username = ?";
$stmt = $conn->prepare($sqlUsers);
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$resultUsers = $stmt->get_result();

if (!$resultUsers) {
    die("Error executing the query for the current user: " . $conn->error);
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
                    <a class="nav-link" href="./osallistujat.php">Osallistujat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./kayttajat.php">Käyttäjät</a>
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

<!-- Update User Information Form -->
<div class="modal fade" id="editUserInfoModal" tabindex="-1" role="dialog" aria-labelledby="editUserInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserInfoModalLabel">Vaihda käyttäjätiedot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="update_pass.php">
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
                    <!-- Password section -->
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Nykyinen salasana*</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Uusi salasana</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                    </div>
                    <!-- End of password section -->
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
                    echo "<td><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editUserInfoModal'>Edit</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }
            // Check if a success message exists in the session
            if (isset($_SESSION["update_success"])) {
                echo '<div class="alert alert-success" role="alert">';
                echo $_SESSION["update_success"];
                echo '</div>';

                // Clear the success message from the session to avoid displaying it again on refresh
                unset($_SESSION["update_success"]);
            }
            // Check if an error message exists in the session
            if (isset($_SESSION["update_error"])) {
                echo '<div class="alert alert-danger" role="alert">';
                echo $_SESSION["update_error"];
                echo '</div>';

                // Clear the error message from the session to avoid displaying it again on refresh
                unset($_SESSION["update_error"]);
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
