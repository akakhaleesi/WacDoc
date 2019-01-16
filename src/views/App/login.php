<?php
if(isset($success)){
  echo "<p>You have been registered successfully </p>";
}
?>

<form action=<?= BASE_URI.'/app/login' ?> method="post">
  <label for="login">Login</label>
  <input type="text" name="login">
  <label for="password">Password</label>
  <input type="password" name="password">
  <input type="submit" value="Log In">
</form>
