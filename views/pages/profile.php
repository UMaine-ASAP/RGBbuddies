<p>Email:<br>
 <?php echo $_SESSION["email"]; ?></p>
<p>First Name:<br>
  <?php echo $_SESSION["firstName"]; ?></p>
<p>Last Name:<br>
  <?php echo $_SESSION["lastName"]; ?></p>

<?php require_once('?controller=user&action=list');?>
