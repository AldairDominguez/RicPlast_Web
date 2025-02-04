<?php
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
   page_require_level(1);
?>
<?php
  $delete_id = delete_by_id('users',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Usuario eliminado.");
      redirect('../views/users.php');
  } else {
      $session->msg("d","No se pudo eliminar el usuario");
      redirect('../views/users.php');
  }
?>
