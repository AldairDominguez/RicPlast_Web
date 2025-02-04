<?php
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
  page_require_level(3);
?>
<?php
  $d_sale = find_by_id('sales',(int)$_GET['id']);
  if(!$d_sale){
    $session->msg("d","Falta el ID de venta.");
    redirect('../views/sales.php');
  }
?>
<?php
  $delete_id = delete_by_id('sales',(int)$d_sale['id']);
  if($delete_id){
      $session->msg("s","Venta eliminada.");
      redirect('../views/sales.php');
  } else {
      $session->msg("d","No se pudo eliminar la venta.");
      redirect('../views/sales.php');
  }
?>
