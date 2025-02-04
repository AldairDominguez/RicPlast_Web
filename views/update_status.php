<?php
require_once('../Model/load.php');
page_require_level(3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE suppliers SET status = '{$status}' WHERE id = {$id}";
    if ($db->query($query)) {
        $session->msg("s", "Estado del proveedor actualizado correctamente.");
    } else {
        $session->msg("d", "Error al actualizar el estado del proveedor.");
    }
    redirect('all_suppliers.php');
}
?>
