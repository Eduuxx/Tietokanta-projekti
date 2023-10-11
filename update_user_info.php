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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["newUsername"];
    $newEmail = $_POST["newEmail"];
    $title = $_POST["title"];
    $country = $_POST["country"];
    $city = $_POST["city"];

    $currentUsername = $_SESSION["username"];

    // Update the user's username and email
    $updateUserQuery = "UPDATE users SET username = ?, email = ? WHERE username = ?";
    $stmt = $conn->prepare($updateUserQuery);
    $stmt->bind_param("sss", $newUsername, $newEmail, $currentUsername);
    $stmt->execute();
    $stmt->close();

    // Update the user's title, country, and city
    $updateSettingsQuery = "UPDATE settings SET name = ? WHERE type IN ('title', 'country', 'city') AND name = ?";
    
    // Update title
    $stmt = $conn->prepare($updateSettingsQuery);
    $stmt->bind_param("ss", $title, $currentUsername);
    $stmt->execute();
    $stmt->close();

    // Update country
    $stmt = $conn->prepare($updateSettingsQuery);
    $stmt->bind_param("ss", $country, $currentUsername);
    $stmt->execute();
    $stmt->close();

    // Update city
    $stmt = $conn->prepare($updateSettingsQuery);
    $stmt->bind_param("ss", $city, $currentUsername);
    $stmt->execute();
    $stmt->close();

    // Redirect to the page where you want to display user data after the update
    header("location: osallistujat.php");
}

$conn->close();
?>