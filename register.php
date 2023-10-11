<?php
# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$username_err = $email_err = $password_err = "";
$username = $email = $password = "";
# Define an array to store participant data
$participants = array();

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // ... (Your existing code for registration)
}

# Fetch participant data from the database
$sql = "SELECT first_name, last_name, email, title, country, city FROM participants";
$result = mysqli_query($link, $sql);

if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $participants[] = $row;
  }
  mysqli_free_result($result);
} else {
  echo "<script>" . "alert('Oops! Something went wrong while fetching participants.')" . "</script>";
}

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  # Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Olkaa hyvä ja kirjoittakaa käyttäjätunnus.";
  } else {
    $username = trim($_POST["username"]);
    if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
      $username_err = "Käyttäjätunnus voi sisältää vain kirjaimia, numeroita ja symboleja, kuten '@', '_' tai '-'.";
    } else {
      # Prepare a select statement
      $sql = "SELECT id FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        # Bind variables to the statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        # Set parameters
        $param_username = $username;

        # Execute the prepared statement 
        if (mysqli_stmt_execute($stmt)) {
          # Store result
          mysqli_stmt_store_result($stmt);

          # Check if username is already registered
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $username_err = "Tämä käyttäjätunnus on jo rekisteröity.";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.')" . "</script>";
        }

        # Close statement 
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validate email 
  if (empty(trim($_POST["email"]))) {
    $email_err = "Ole hyvä ja lisää sähköpostiosoitteesi";
  } else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Ole hyvä ja syötä toimiva sähköpostiosoite.";
    } else {
      # Prepare a select statement
      $sql = "SELECT id FROM users WHERE email = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        # Bind variables to the statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        # Set parameters
        $param_email = $email;

        # Execute the prepared statement 
        if (mysqli_stmt_execute($stmt)) {
          # Store result
          mysqli_stmt_store_result($stmt);

          # Check if email is already registered
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $email_err = "Tämä sähköposti on käytettävissä!.";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        }

        # Close statement
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Kirjoita sun salasanasi!.";
  } else {
    $password = trim($_POST["password"]);
    if (strlen($password) < 8) {
      $password_err = "Salasana pitää olla 8 kirjainta pitkä.";
    }
  }

  # Check input errors before inserting data into database
  if (empty($username_err) && empty($email_err) && empty($password_err)) {
    # Prepare an insert statement
    $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind varibales to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

      # Set parameters
      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      # Execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        echo "<script>" . "alert('Registeration completed successfully. Login to continue.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php';" . "</script>";
        exit;
      } else {
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
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
  <script defer src="./js/script.js"></script>
</head>

<body>
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <div class="form-wrap border rounded p-4">
          <h1>Rekisteröidy</h1>
        
          <!-- form starts here -->
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <!-- <label for="username" class="form-label">Käyttäjänimi *</label>-->
              <input type="text" class="form-control" name="username" id="username" placeholder="Käyttäjänimi *" value="<?= $username; ?>">
              <small class="text-danger"><?= $username_err; ?></small>
            </div>
            <div class="mb-3">
              <!-- <label for="email" class="form-label">Sähköposti *</label>-->
              <input type="email" class="form-control" name="email" id="email" placeholder="Sähköposti *" value="<?= $email; ?>">
              <small class="text-danger"><?= $email_err; ?></small>
            </div>
            <div class="mb-2">
              <!-- <label for="password" class="form-label">Salasana *</label> -->
              <input type="password" class="form-control" name="password" id="password" placeholder="Salasana *" value="<?= $password; ?>">
              <small class="text-danger"><?= $password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Näytä Salasana</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="REKISTERÖIDY">
            </div>
            <p class="mb-0">Sinulla on käyttäjä? <a href="./login.php">Kirjaudu sisään</a></p>
          </form>
          <!-- form ends here -->
        </div>
      </div>
    </div>
  </div>
</body>

</html>