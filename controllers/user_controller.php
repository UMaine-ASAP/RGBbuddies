<?php
require_once('models/user.php');

class UserController {

///////////////////////////////////////////////////////////////////////// INDEX
  function index() {
    require_once('views/user/userRegistration.php');
  }

///////////////////////////////////////////////////////////////////////// REGISTER
  function register() {
    require_once('views/user/userRegistration.php');

    if (isset($_POST['firstName'])) {
      $out = '';
      $email = $this->clean($_POST['email']);
      $password = $this->clean($_POST['password']);
      $firstName = $this->clean($_POST['firstName']);
      $lastName = $this->clean($_POST['lastName']);
      $favColor = $this->hex2rgb($_POST['favColor']);

      $outcome = User::create($email, $password, $firstName, $lastName, $favColor);

      switch($outcome[0]) {
        case 1: //succesful login
          $out = $outcome[1];
        case 2: // user already exists with email
          $out = $outcome[1]; //TODO: add forgot password function after failed login attempt
      }
      User::login($email, $password);
      header('Location: index.php');
    }
  }

///////////////////////////////////////////////////////////////////////// LOGIN
  function login() {
    require_once('views/user/userLogin.php');

    if(isset($_SESSION['token']))
      User::logout($_SESSION['token']);

    if (isset($_POST['email'])) {
      $email = $this->clean($_POST['email']);
      $password = $this->clean($_POST['password']);

      $outcome = User::login($email, $password);
      if ($outcome[0] != 1) {
        $_SESSION['message'] = $outcome[1];
        echo $_SESSION['message'];
      }
      else {
        $userData = $outcome[1];
        $_SESSION['email'] = $userData[0];
        $_SESSION['firstName'] = $userData[1];
        $_SESSION['lastName'] = $userData[2];
        $_SESSION['favColor'] = $userData[3];
        $_SESSION['token'] = $userData[4];
        header('Location: index.php');

      }
    }
  }

///////////////////////////////////////////////////////////////////////// LOGOUT
  function logout() {
    if(isset($_SESSION['token']))
      User::logout($_SESSION['token']);
    session_unset();
    header('Location: index.php');
  }

///////////////////////////////////////////////////////////////////////// LIST USERS
  function list() {
    $users = User::all()[1];
    require_once('views/user/userList.php');
  }

///////////////////////////////////////////////////////////////////////// CONVERT COLOR HEX TO RGB
  private function hex2rgb($hex) {
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
    return $rgb; // returns an array with the rgb values
  }

///////////////////////////////////////////////////////////////////////// CLEAN INPUT FOR SECURTIY
  private function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
}
 ?>
