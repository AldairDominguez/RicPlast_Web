<?php
require_once('../Model/load.php');
// Verificar el nivel de permiso del usuario
page_require_level(2);

// Obtener el ID de la imagen
$find_media = find_by_id('media', (int)$_GET['id']);
$photo = new Media();

// Verificar si la imagen está siendo utilizada en algún producto
$image_in_use = find_by_sql("SELECT * FROM products WHERE media_id = {$find_media['id']} LIMIT 1");

if ($image_in_use) {
    // Si la imagen está en uso, mostrar un mensaje y redirigir a la página de medios
    $session->msg('d', 'No se puede eliminar porque esta imagen está siendo usada en un producto.');
    redirect('/RicPlast3.1/views/media.php');
} else {
    // Si no está en uso, proceder con la eliminación
    if ($photo->media_destroy($find_media['id'], $find_media['file_name'])) {
        $session->msg("s", "La foto ha sido eliminada.");
        redirect('/RicPlast3.1/views/media.php');
    } else {
        $session->msg("d", "La eliminación de la foto falló o falta.");
        redirect('/RicPlast3.1/views/media.php');
    }
}
?>
