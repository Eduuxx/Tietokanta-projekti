<?php
// Initialize the session
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    header("location: login.php");
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

// Function to add an item to the database
function addItem($type, $name, $conn) {
    $stmt = $conn->prepare("INSERT INTO settings (type, name) VALUES (?, ?)");
    $stmt->bind_param("ss", $type, $name);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to delete an item from the database
function deleteItem($itemId, $conn) {
    $stmt = $conn->prepare("DELETE FROM settings WHERE id = ?");
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to edit an item in the database
function editItem($itemId, $newName, $conn) {
    $stmt = $conn->prepare("UPDATE settings SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $newName, $itemId);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Handle form submissions for adding, editing, and deleting items
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["addTitle"])) {
        $title = $_POST["title"];
        if (addItem("title", $title, $conn)) {
            echo "<p>Titteli added successfully.</p>";
        } else {
            echo "<p>Error adding titteli.</p>";
        }
    } elseif (isset($_POST["addCountry"])) {
        $country = $_POST["country"];
        if (addItem("country", $country, $conn)) {
            echo "<p>Osasto added successfully.</p>";
        } else {
            echo "<p>Error adding osastoa.</p>";
        }
    } elseif (isset($_POST["addCity"])) {
        $city = $_POST["city"];
        if (addItem("city", $city, $conn)) {
            echo "<p>Työpaikka added successfully.</p>";
        } else {
            echo "<p>Error adding työpaikka.</p>";
        }
    } elseif (isset($_POST["deleteItem"])) {
        $itemId = $_POST["itemId"];
        if (deleteItem($itemId, $conn)) {
            echo "<p>Item deleted successfully.</p>";
        } else {
            echo "<p>Error deleting item.</p>";
        }
    } elseif (isset($_POST["editItem"])) {
        $itemId = $_POST["itemId"];
        $newName = $_POST["newName"];
        if (editItem($itemId, $newName, $conn)) {
            echo "Item edited successfully.";
            exit; // Exit without additional output, as this response will be handled by JavaScript
        } else {
            echo "Error editing item.";
            exit; // Exit without additional output, as this response will be handled by JavaScript
        }
    }
}
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

    <!-- Settings Boxes -->
    <div class="settings-box">
        <h3>Tittelit</h3>
        <div class="item">
            <form method="post">
                <input type="text" placeholder="Titteli" name="title">
                <button type="submit" name="addTitle">+</button>
            </form>
        </div>
        <div class="existing-items">
            <?php
            // Retrieve and display existing titles with delete and edit buttons
            $sql = "SELECT * FROM settings WHERE type = 'title'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['name']} ";
                    echo "<form method='post' style='display: inline;'>";
                    echo "<input type='hidden' name='itemId' value='{$row['id']}'>";
                    echo "<button type='submit' name='deleteItem'>🗑️</button>";
                    echo "</form>";
                    echo "<button onclick=\"editItem('{$row['id']}')\">✏️</button>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>

    <div class="settings-box">
        <h3>Osasto</h3>
        <div class="item">
            <form method="post">
                <input type="text" placeholder="Osasto" name="country">
                <button type="submit" name="addCountry">+</button>
            </form>
        </div>
        <div class="existing-items">
            <?php
            // Retrieve and display existing countries with delete and edit buttons
            $sql = "SELECT * FROM settings WHERE type = 'country'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['name']} ";
                    echo "<form method='post' style='display: inline;'>";
                    echo "<input type='hidden' name='itemId' value='{$row['id']}'>";
                    echo "<button type='submit' name='deleteItem'>🗑️</button>";
                    echo "</form>";
                    echo "<button onclick=\"editItem('{$row['id']}')\">✏️</button>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>

    <div class="settings-box">
        <h3>Työpaikat</h3>
        <div class="item">
            <form method="post">
                <input type="text" placeholder="Työpaikat" name="city">
                <button type="submit" name="addCity">+</button>
            </form>
        </div>
        <div class="existing-items">
            <?php
            // Retrieve and display existing cities with delete and edit buttons
            $sql = "SELECT * FROM settings WHERE type = 'city'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['name']} ";
                    echo "<form method='post' style='display: inline;'>";
                    echo "<input type='hidden' name='itemId' value='{$row['id']}'>";
                    echo "<button type='submit' name='deleteItem'>🗑️</button>";
                    echo "</form>";
                    echo "<button onclick=\"editItem('{$row['id']}')\">✏️</button>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>
    </div>

    
</body>
</html>
