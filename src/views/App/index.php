<?php
if(isset($_SESSION['user_id'])){
  echo $_SESSION['user_id'];
}
?>

<ul>
  <li><a href=<?= BASE_URI.'/app/parameters' ?>>Parameters</a></li>
  <li><a href=<?= BASE_URI.'/doc/index' ?>>Documents</a></li>
  <li><a href=<?= BASE_URI.'/app/logout' ?>>Log out</a></li>
</ul>
