<?php

  session_start();

  define('BASE_URI', str_replace('\\', '/', substr(__DIR__,
  strlen($_SERVER['DOCUMENT_ROOT']))));
  require_once(implode(DIRECTORY_SEPARATOR, ['Core', 'autoload.php']));

  $request = new Core\Core();
  $request->run();

?>

<pre>
<?php
// var_dump($_POST);
// var_dump($_GET);
// var_dump($_SERVER);
?>
</pre>
