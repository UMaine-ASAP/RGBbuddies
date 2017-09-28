<DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <header>
      <a href="index.php">Home</a>
      <a href = '?controller=posts&action=index'>Posts</a>
      <a href = '?controller=user&action=index'>Register</a>
    </header>

    <?php require_once("routes.php");
     ?>

    <footer>
      Copyright
    </footer>
  </body>
</html>
