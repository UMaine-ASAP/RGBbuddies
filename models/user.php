<?php

Class User {
  public $id;
  public $email;
  public $firstName;
  public $lastName;
  public $favColor;

  public function __construct($id, $email, $firstName, $lastName, $favColor) {
    $this->id = $id;
    $this->email = $email;
    $this->firstName = $firstName;
    $this->$lastName = $lastName;
    $this->favColor = $favColor;
  }

///////////////////////////////////////////////////////////////////////// CREATE USER
  public static function create($em, $pw, $fn, $ln, $color) {
    $errorCode;
    $message;
    $currentUsers = User::all()[1];
    $conflict = false;

    foreach($currentUsers as $user) {
      if ($user->email == $em)
        $conflict = true;
    }

    if ($conflict) {
      $errorCode = 2;
      $message = "Email address is already registered";
    }
    else {
      $salted = password_hash($pw, PASSWORD_DEFAULT);
      $db = Db::getInstance();
      $sql = "INSERT INTO user (email, password, firstName, lastName, red, green, blue) VALUES (?, ?, ?, ?, ?, ?, ?)";
      try {
        $stmt = $db->prepare($sql);
        $data = array($em, $salted, $fn, $ln, $color[0], $color[1], $color[2]);
        $stmt->execute($data);
        $errorCode = 1;
        $message = "Account created";
      }
      catch(PDOException $e) {
        $errorCode = $e->getCode();
        $message = $e->getMessage();
      }
    }
    return array($errorCode, $message);
  }

///////////////////////////////////////////////////////////////////////// LOGIN
 public static function login($email, $password) {
   $errorCode;
   $message;
   $userID = '';
   $db = Db::getInstance();
   $sql = "SELECT * FROM user WHERE email = ?";
   $data = array($email);
   try {
     $stmt = $db->prepare($sql);
     $stmt->execute($data);
     $result = $stmt->fetch(PDO::FETCH_ASSOC);
     if ($result) {
       $hash = $result['password'];
       if(password_verify($password, $hash)) {
         $characters = "0123456789QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm";
         $token = '';
         for ($i = 0; $i < 20; $i++)
           $token .= $characters[mt_rand(0, 61)];
         User::insertToken($result['UserID'], $token);
         $errorCode = 1;
         $message = array($result['firstName'],$result['lastName'],$result['middleInitial'],$token, $result['userID']);
       }
       else {
         $errorCode = 5;
         $message = "Your email and/or password were incorrect. Please try again."
       }
     }
   }
   catch(PDOException $e) {
        $errorCode  = $e->getCode();
        $message    = $e->getMessage();
      }
   return array($errorCode, $message);
 }

///////////////////////////////////////////////////////////////////////// INSERT TOKEN
  function insertToken($userID, $token) {
    $errorCode;
    $message;
    $db = Db::getInstance();
    $sql = 'UPDATE user SET token = ? WHERE email = ?';
    try {
      $stmt = $db->prepare($sql);
      $data = array($token, $userID);
      $stmt->execute($data);
      $errorCode = 1;
      $message = "Token update successful";
    }
    catch (PDOException $e) {
      $errorCode =  $e->getCode();
      $message =    $e->getMessage();
    }
    return array($errorCode, $message);
  }

///////////////////////////////////////////////////////////////////////// RETURN LIST OF ALL USERS
  public static function all() {
    $errorCode;
    $message;
    $db = Db::getInstance();
    $sql = "SELECT * FROM user";
    $userList = array();
    try {
      $stmt = $db->prepare($sql);
      $stmt->execute();
      while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userList[] = new User($result['userID'], $result['email'], $result['firstName'], $result['lastName'], array($result['red'], $result['green'], $result['blue']));
      }
      $errorCode = 1;
      $message = $userList;
    }
    catch(PDOException $e) {
      $errorCode = $e->getCode();
      $message = $e->getMessage();
    }
    return array($errorCode, $message);
  }

}
 ?>
