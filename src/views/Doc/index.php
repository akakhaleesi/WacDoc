<ul>
  <li><a href=<?= BASE_URI.'/app/index' ?>>Index</a></li>
  <li><a href=<?= BASE_URI.'/doc/create' ?>>New file</a></li>
</ul>

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
