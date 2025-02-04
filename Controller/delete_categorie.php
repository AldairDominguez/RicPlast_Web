<?php
require_once('../Model/load.php');
// Verifica el nivel de permiso para ver esta página
page_require_level(1);

// Obtiene la categoría por su ID
$categorie = find_by_id('categories', (int)$_GET['id']);
if (!$categorie) {
    $session->msg("d", "ID de categoría faltante.");
    redirect('../views/categorie.php');
}

// Verifica si la categoría está en uso en la tabla de productos
$sql = "SELECT COUNT(*) AS total FROM products WHERE categorie_id = '{$categorie['id']}'";
$result = $db->query($sql);
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
    // Si la categoría está en uso, muestra un mensaje de error y redirige
    $session->msg("d", "Categoría en uso, no se puede eliminar.");
    redirect('../views/categorie.php');
} else {
    // Si la categoría no está en uso, procede con la eliminación
    $delete_id = delete_by_id('categories', (int)$categorie['id']);
    if ($delete_id) {
        $session->msg("s", "Categoría eliminada.");
        redirect('../views/categorie.php');
    } else {
        $session->msg("d", "No se pudo eliminar la categoría.");
        redirect('../views/categorie.php');
    }
}
?>
