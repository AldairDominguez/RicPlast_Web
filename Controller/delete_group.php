<?php
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
  page_require_level(1);

  $role_id = (int)$_GET['id'];

  // Verifica si el rol es uno de los primeros tres
  if ($role_id <= 3) {
      $session->msg("d", "Estos roles no pueden ser eliminados");
      redirect('../views/group.php');
  } else {
      // Procede con la eliminación si el rol es mayor a 3
      $delete_id = delete_by_id('user_groups', $role_id);
      if ($delete_id) {
          $session->msg("s", "El Usuario ha sido eliminado.");
          redirect('../views/group.php');
      } else {
          $session->msg("d", "La eliminación del Usuario falló o falta ");
          redirect('../views/group.php');
      }
  }
?>
