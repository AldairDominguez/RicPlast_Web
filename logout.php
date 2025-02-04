<?php
  require_once('Model/load.php');
  if(!$session->logout()) {redirect("index.php");}
?>
