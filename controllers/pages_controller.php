<?php
  class PagesController {
    public function home() {
      if (isset($_SESSION['token']))
        require_once('views/pages/profile.php');
      else
        require_once('views/pages/home.php');
    }

    public function error() {
      require_once('views/pages/error.php');
    }
  }
?>
