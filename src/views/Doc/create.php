<?php
if(isset($errors)){
  echo "<div>";
  foreach($errors as $error){
    echo "<p>$error</p>";
  }
  echo "</div>";
}
?>

<form action=<?= BASE_URI.'/doc/save' ?> method="post">
  <input type="submit" value="Save">
  <label for="doc_name">Document name:</label>
  <input name="doc_name" type="text" value=<?php if(isset($doc_name)) echo $doc_name; ?>>
  <textarea name="doc_content"><?php if(isset($doc_content)) echo $doc_content; ?></textarea>
</form>
