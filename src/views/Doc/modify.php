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
  <li><a href=<?= BASE_URI.'/doc/index' ?>>Index</a></li>
</ul>

<form action=<?= BASE_URI.'/doc/modify' ?> method="post">
  <input name="type" type="hidden" value="save_modif">
  <input name="doc_id" type="hidden" value=<?php if(isset($doc_id)) echo $doc_id; ?>>
  <input type="submit" value="Save">
  <label for="doc_name">Document name:</label>
  <input name="doc_name" type="text" value=<?php if(isset($doc_name)) echo $doc_name; ?> readonly>
  <textarea name="doc_content"><?php if(isset($doc_content)) echo $doc_content; ?></textarea>
</form>
