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
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand" href="#"><img src="logo.png" alt="Logo" width="40"> Your Logo</a>
      
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

  <div class="container">
    <div class="alert alert-success my-5">
      Olet kirjautunut sisään!
    </div>
    <!-- User profile -->
    <div class="row justify-content-center">
      <div class="col-lg-5 text-center">
        <img src="./img/blank-avatar.jpg" class="img-fluid rounded" alt="User avatar" width="180">
        <h4 class="my-4">Testaus, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
        <a href="./logout.php" class="btn btn-primary">KIRJAUDU ULOS</a>
      </div>
    </div>
  </div>
</body>

</html>