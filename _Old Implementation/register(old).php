<html>
<body>

  <?php
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


     $emailErr = $passwdErr = $rePasswdErr = $firstNameErr = $lastNameErr = "";
     $email = $passwd = $rePasswd = $firstName = $lastName = $favColor = "";
     $valuesFilled = false;

     if($_SERVER["REQUEST_METHOD"] == "POST") {

       if (empty($_POST["email"]) || !filter_var(test_input($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
         $emailErr = "<br>Please enter a vaild email address.";
       }
       else {
         $email = test_input($_POST["email"]);
       }

       if (empty($_POST["passwd"])) {
         $passwdErr = "<br>Please enter a password.";
       }
       else {
         $passwd = test_input($_POST["passwd"]);
       }

       if (empty($_POST["passwd"]) || test_input($_POST["rePasswd"]) != $passwd) {
         $rePasswdErr = "<br>Passwords do not match.";
       }
       else {
         $rePasswd = test_input($_POST["rePasswd"]);
       }

       if (empty($_POST["firstName"])) {
         $firstNameErr = "<br>Please enter your first name.";
       }
       else {
         $firstName = test_input($_POST["firstName"]);
       }

       if (empty($_POST["lastName"])) {
         $lastNameErr = "<br>Please enter your last name.";
       }
       else {
         $lastName = test_input($_POST["lastName"]);
       }

       $favColor = hex2rgb(test_input($_POST["favColor"]));

       $valuesFilled = !empty($email) && !empty($passwd) &&!empty($rePasswd) && !empty($firstName) && !empty($lastName) && !empty($favColor);

       $stmt = $conn->prepare("SELECT userID FROM user WHERE email = ?");
       $data = array($email);
       $stmt->execute($data);
       $row = $stmt->fetch();

      if ($row) {
        $emailErr = "<br>A user with this email already exists.";
      }
      else if($valuesFilled) {
        //add user to user table
         $stmt = $conn->prepare("INSERT INTO user (email, password, firstName, lastName, red, green, blue)
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
         $stmt->execute(array($email, $passwd, $firstName, $lastName, $favColor[0], $favColor[1], $favColor[2]));

         $stmt = $conn->prepare("SELECT userID FROM user WHERE email = ?");
         $data = array($email);
         $stmt->execute($data);
         $row = $stmt->fetch();
         session_start();
         $_SESSION['id'] = $row["userID"];




/*
         //check color table to see if color exists, if not add it
         $stmt = $conn->prepare("SELECT colorID FROM color WHERE name = ?");
         $data = array($favColor);
         $stmt->execute($data);
         $row = $stmt->fetch();
         if ($row) {
           //add user color relation
           $stmt = $conn->prepare("INSERT INTO user_color (userID, colorID)
                                    VALUES (?, ?)");
           $data = array($_SESSION["id"], $row["colorID"]);
           $stmt->execute($data);
         }
         else  {
           //add new color to color table
           $stmt = $conn->prepare("INSERT INTO color (name)
                                    VALUES (?)");
           $data = array($favColor);
           $stmt->execute($data);

           //add user color relation
           $stmt = $conn->prepare("SELECT colorID FROM color WHERE name = ?");
           $data = array($favColor);
           $stmt->execute($data);
           $row = $stmt->fetch();
           $stmt = $conn->prepare("INSERT INTO user_color (userID, colorID)
                                    VALUES (?, ?)");
           $data = array($_SESSION["id"], $row["colorID"]);
           $stmt->execute($data);
         }*/

         header("location: welcome.php");
      }
     }

     function test_input($data) {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
     }

     //found this online, fingers crossed it works UPDATE: it does
     function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
         $r = hexdec(substr($hex,0,1).substr($hex,0,1));
         $g = hexdec(substr($hex,1,1).substr($hex,1,1));
         $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       }
       else {
         $r = hexdec(substr($hex,0,2));
         $g = hexdec(substr($hex,2,2));
         $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       //return implode(",", $rgb); // returns the rgb values separated by commas
       return $rgb; // returns an array with the rgb values
     }

    ?>

<h1>Register</h1>

<!-- Registration Form -->
<form action="<?php echo htmlspecialchars($_SERVER[PHP_SELF]);?>" method="post">
  <p>Email:<br>
    <input type="text" name="email">
    <span class="error"> <?php echo $emailErr?>
  </p>
  <p>Password:<br>
    <input type="password" name=passwd>
    <span class="error"> <?php echo $passwdErr?>
  </p>
  <p>Confirm Password:<br>
    <input type="password" name=rePasswd>
    <span class="error"> <?php echo $rePasswdErr?>
  </p>
  <p>First Name:<br>
    <input type="text" name="firstName">
    <span class="error"> <?php echo $firstNameErr?>
  </p>
  <p>Last Name:<br>
    <input type="text" name="lastName">
    <span class="error"> <?php echo $lastNameErr?>
  </p>
  <p>Favorite Color:<br>
    <input type="color" name="favColor" value="#FF0000">
    <!-- <datalist id="colors"> -->
      <?php /*
        global $conn;
        $sql = "SELECT * FROM color ORDER BY name";
        $result = $conn->query($sql);

        foreach($result as $row) {
          echo "<option value= " . $row["name"] . "> ";
        }
      */?>
    <!-- </datalist> -->
   </input>
  </p>
  <input type="submit" value="Register">
</form>

</body>
</html>
