<?php
define("DB_SERVER", "localhost");
define("DB_USERNAME", "Edu");
define("DB_PASSWORD", "Eduard123");
define("DB_NAME", "edu");

# Connection
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

# Check connection
if (!$link) {
  die("Connection failed: " . mysqli_connect_error());
}