<DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <header>
      <a href="index.php">Home</a>
      <a href = '?controller=user&action=login'>Login/Sign Up</a>
      <a href = '?controller=user&action=logout'>Logout</a>
    </header>

    <?php require_once("routes.php");
     ?>

    <footer>
      Copyright
    </footer>
  </body>
</html>
