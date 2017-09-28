<h2>New User Registration</h2>

<div>
  <form action='?controller=user&action=register' method='post'>
    <input class='standard' type ='text' name='email' placeholder="Email Address" autofocus required><br>
    <input class='standard' type ='password' name='password' placeholder="Password" autofocus required><br>
    <input class='standard' type ='password' name='passwordConfirm' placeholder="Confirm Password" autofocus required><br>
    <input class='standard' type ='text' name='firstName' placeholder="First Name" autofocus required><br>
    <input class='standard' type ='text' name='lastName' placeholder="Last Name" autofocus required><br>
    Favorite Color:<input class='standard' type="color" name="favColor" value="#FF0000" autofocus required><br>
    <input class='standard' id='submission' type='submit' value='Add User'>
  </form>
</div>
