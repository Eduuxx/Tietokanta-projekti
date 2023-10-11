<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
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

  <div class="container">
    <div class="alert alert-success my-5">
      Olet kirjautunut sisään!
    </div>
    <!-- User profile -->
    <div class="row justify-content-center">
      <div class="col-lg-5 text-center">
        <img src="../images/turku.png" class="img-fluid rounded" alt="User avatar" width="180">
        <h4 class="my-4">Tervetuloa, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
        <a href="./logout.php" class="btn btn-primary">KIRJAUDU ULOS</a>
      </div>
    </div>
  </div>
</body>

</html>