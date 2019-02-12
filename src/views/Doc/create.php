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

<form id="save" action=<?= BASE_URI.'/doc/save' ?> method="post">
  <input id="save_doc" type="submit" value="Save">
  <input name="doc_content" type="hidden">
  <label for="doc_name">Document name:</label>
  <input name="doc_name" type="text" value=<?php if(isset($doc_name)) echo $doc_name; ?>>
</form>

<button onclick="document.execCommand('undo',false,null);">undo</button>
<button onclick="document.execCommand('redo',false,null);">redo</button>
<button id="bold">bold</button>
<button id="italic">italic</button>
<button onclick="document.execCommand('underline',false,null);">underline</button>
<button onclick="document.execCommand('justifyLeft',false,null);">left</button>
<button onclick="document.execCommand('justifyCenter',false,null);">center</button>
<button onclick="document.execCommand('justifyRight',false,null);">right</button>
<button id="list1">list 1</button>
<button id="list2">list 2</button>
<button id="backcolor">back color</button>
<button id="color">color</button>
<input id="c" type="color"></input>
<div><input id="n" type="number"></input><button id="size">ok</button></div>

<div id="editor" style="height:500px;" contenteditable>
  <?php if(isset($doc_content)) echo $doc_content; ?>
</div>
