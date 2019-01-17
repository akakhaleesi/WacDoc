<?php
if(isset($success)){
  echo "<p>You have been registered successfully </p>";
}
if(isset($errors)){
  echo "<div>";
  foreach($errors as $error){
    echo "<p>$error</p>";
  }
  echo "</div>";
}
?>

<ul>
  <li><a href=<?= BASE_URI.'/app/register' ?>>Register</a></li>
</ul>

<form action=<?= BASE_URI.'/app/login' ?> method="post">
  <label for="login">Login</label>
  <input type="text" name="login">
  <label for="password">Password</label>
  <input type="password" name="password">
  <input type="submit" value="Log in">
</form>
