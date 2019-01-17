<form action=<?= BASE_URI.'/app/delete'?> method="post">
  <input name="user_id" type="hidden" value=<?= $_SESSION['user_id'] ?>>
  <input type="submit" value="Delete this account">
</form>
