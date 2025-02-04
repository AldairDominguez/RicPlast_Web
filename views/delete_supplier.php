<?php
require_once('../Model/load.php');
page_require_level(3);

$id = (int)$_GET['id'];

// Verificar si el proveedor está relacionado con algún producto
$product_count = find_by_sql("SELECT COUNT(*) AS count FROM products WHERE supplier_id = '{$id}'");
if ($product_count[0]['count'] > 0) {
    $session->msg("d", "El proveedor no se puede eliminar porque está asociado a uno o más productos.");
    redirect('all_suppliers.php');
} else {
    // Si no hay relación, proceder con la eliminación
    $query = "DELETE FROM suppliers WHERE id = {$id}";
    if ($db->query($query)) {
        $session->msg("s", "Proveedor eliminado correctamente.");
    } else {
        $session->msg("d", "Error al intentar eliminar el proveedor.");
    }
    redirect('all_suppliers.php');
}
?>
