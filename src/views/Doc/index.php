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
  <li><a href=<?= BASE_URI.'/app/index' ?>>Index</a></li>
  <li><a href=<?= BASE_URI.'/doc/create' ?>>New file</a></li>
</ul>

<form action=<?= BASE_URI.'/doc/upload' ?> method="post"  enctype="multipart/form-data">
  <input name="file" type="file">
  <input type="submit" value="Upload file">
</form>

<?php
if(isset($files)){
  echo "<div>";
  foreach($files as $file){
    echo "<p>".$file['name']."</p>";
    echo "<form action='".BASE_URI."/doc/modify' method='post'>";
    echo "<input name='doc_id' type='hidden' value=".$file['id'].">";
    echo "<input type='submit' value='Modify file'>";
    echo "</form>";
  }
  echo "</div>";
}
?>
