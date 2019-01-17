<?php
if(isset($errors)){
  echo "<div>";
  foreach($errors as $error){
    echo "<p>$error</p>";
  }
  echo "</div>";
}
?>

<ul>
  <li><a href=<?= BASE_URI.'/app/login' ?>>Log in</a></li>
</ul>

<form action=<?= BASE_URI.'/app/register' ?> method="post">
  <label for="login">Login</label>
  <input type="text" name="login">
  <label for="password">Password</label>
  <input type="password" name="password">
  <label for="password">Password Confirmation</label>
  <input type="password" name="password2">
  <input type="submit" value="Register">
</form>
