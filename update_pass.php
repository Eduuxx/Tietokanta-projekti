<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "Edu";
    $password = "Eduard123";
    $database = "edu";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $currentUsername = $_POST["currentUsername"];
    $newUsername = $_POST["newUsername"];
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];

    // Query the users table to verify the current password
    $sqlVerifyPassword = "SELECT username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sqlVerifyPassword);
    $stmt->bind_param("s", $currentUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedCurrentPassword = $row['password'];

        // Verify the current password
        if (password_verify($currentPassword, $hashedCurrentPassword)) {
            // Password is correct, update the username and/or password
            $updateSql = "UPDATE users SET username = ?, password = ? WHERE username = ?";
            $stmt = $conn->prepare($updateSql);
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
            $stmt->bind_param("sss", $newUsername, $hashedNewPassword, $currentUsername);
            if ($stmt->execute()) {
                // Update session data if necessary
                $_SESSION["username"] = $newUsername;

                // Set a success message in the session
                $_SESSION["update_success"] = "Changes successfully saved.";

                // Redirect to the user panel or a confirmation page
                header("location: kayttajat.php");
            } else {
                echo "Update failed: " . $conn->error;
            }
        } else {
            // Password is incorrect, handle the error (e.g., display an error message)
            $_SESSION["update_error"] = "Incorrect password.";
            header("location: kayttajat.php");
        }
    } else {
        // Username not found, handle the error
        $_SESSION["update_error"] = "Username not found.";
        header("location: kayttajat.php");
    }
}
?>