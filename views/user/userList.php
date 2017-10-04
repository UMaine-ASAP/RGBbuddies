<table>
<tr>
  <th>Name</th>
  <th>Favorite Color</th>
</tr>
  <?php foreach($users as $user) {
    echo '<tr>';
    echo '<td>' . $user['firstName'] . " ". $user['lastName'] . '</td>';
    echo '<td>' . $user['favColor'] . '</td>';
    echo '</tr>';
  } ?>
</table>
