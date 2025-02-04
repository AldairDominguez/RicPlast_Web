<?php
require_once('../Model/load.php');
// Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
page_require_level(2);
?>
<?php
$product = find_by_id('products', (int)$_GET['id']);
if (!$product) {
    $session->msg("d", "Falta la Id del producto.");
    redirect('../views/product.php');
}

// Verificar si el producto ha sido vendido
$sales = find_by_sql("SELECT * FROM sales WHERE product_id = {$product['id']} LIMIT 1");
if (!empty($sales)) {
    $session->msg("d", "Este producto ya tiene registros de venta y no se puede eliminar.");
    redirect('../views/product.php');
} else {
    // Eliminar el producto si no ha sido vendido
    $delete_id = delete_by_id('products', (int)$product['id']);
    if ($delete_id) {
        $session->msg("s", "Producto eliminado exitosamente.");
        redirect('../views/product.php');
    } else {
        $session->msg("d", "La eliminación del producto falló.");
        redirect('../views/product.php');
    }
}
?>
