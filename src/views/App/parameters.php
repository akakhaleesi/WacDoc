<ul>
  <li><a href=<?= BASE_URI.'/app/index' ?>>Index</a></li>
</ul>

<form action=<?= BASE_URI.'/app/delete'?> method="post">
  <input name="user_id" type="hidden" value=<?= $_SESSION['user_id'] ?>>
  <input type="submit" value="Delete this account">
</form>
