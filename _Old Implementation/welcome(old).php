<?php session_start(); ?>

<html>
<body>

<?php
//display php errors in browser
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";

$user = "root";
$pass = "root";

try {
  $conn = new PDO("mysql:host=$host;dbname=kashyyyk", $user, $pass);
  /*$conn -> exec("CREATE DATABASE `$db`;
                  CREATE USER '$user'@'$host' IDENTIFIED BY '$pass';
                  GRANT ALL ON `$db`.* TO '$user'@'$host';
                  FLUSH PRIVILEGES;");*/
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$userID = $_SESSION["id"];

$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$data = array($userID);
$stmt->execute($data);
$row = $stmt->fetch();

//return favorite color of user with input ID
function getFavColor($userID) {
  global $conn;
  $stmt = $conn->prepare("SELECT red, blue, green FROM user WHERE userID = ?");
  $data = array($userID);
  $stmt->execute($data);
  $row = $stmt->fetch();
  $red = $row["red"];
  $green = $row["green"];
  $blue = $row["blue"];
  return "rgb($red, $green, $blue)";

}
 ?>

  <h1 style="color:" . <?php echo getFavColor($_SESSION["id"])?> . ";">Welcome</h1>

  <p>Email:<br>
   <?php echo $row["email"]; ?></p>
  <p>First Name:<br>
    <?php echo $row["firstName"]; ?></p>
  <p>Last Name:<br>
    <?php echo $row["lastName"]; ?></p>
  <p>Favorite Color:<br>
    <div style="width:500px;height:100px;background_color:" . <?php echo getFavColor($_SESSION["id"])?> .";">This is a rectangle!</div>




</body>
</html>
