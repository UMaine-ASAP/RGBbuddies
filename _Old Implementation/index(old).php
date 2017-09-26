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

  $emailErr = $passwdErr = "";
  $email = $passwd = "";

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    //confirm entry is a valid email address
    if (empty($_POST["email"])) {
      $emailErr = "<br>Please enter your email.";
    }
    else if (!filter_var(test_input($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
      $emailErr = "<br>Please enter a valid email address.";
    }
    else {
      $email = test_input($_POST["email"]);
    }

    //confirm password is not empty
    if (empty($_POST["passwd"])) {
      $passwdErr = "<br>Please enter your password.";
    }
    else {
      $passwd = test_input($_POST["passwd"]);
    }

  //validate credentials
  if (empty($emailErr) && empty($passwdErr)) {
    $sql = "SELECT userID, email, password FROM user WHERE email = :email";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);

      if ($stmt->execute()) {
        $row = $stmt->fetch();
        //check if username exists, if yes verify password
        if ($row) {
            if ($row["password"] == $passwd) {
              session_start();
              $_SESSION['id'] = $row["userID"];
              header("location: welcome.php");
            }
            else {
              $passwdErr = "<br>The password you entered is incorrect.";
            }
          }
          else {
            $emailErr = "<br>No account found with that email.";
          }
        }

      }
      else {
        echo "Opps! Something went wrong. Please try again later.";
      }
    }

  }


  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 ?>

<!-- Login form -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
  <p>Email:<br>
    <input type="text" name="email">
    <span class="error"> <?php echo $emailErr?>
  </p>
  <p>Password:<br>
    <input type="password" name="passwd">
    <span class="error"> <?php echo $passwdErr?>
  </p>
  <input type="submit" value="Log In">
</form>

<a href="register.php">Register</a>
</body>
</html>
