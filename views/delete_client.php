<?php
require_once('../Model/load.php');
page_require_level(3); // Cambia el nivel de acceso según sea necesario

// Verifica que se haya pasado un ID válido
if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];

    // Función para verificar si un cliente tiene ventas
    function has_sales($client_id) {
        global $db;
        $sql = "SELECT COUNT(*) AS total FROM sales WHERE client_id = '{$client_id}'";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }

    // Verifica si el cliente tiene ventas
    if (has_sales($client_id)) {
        $session->msg("d", "No se puede eliminar este cliente porque tiene ventas asociadas.");
        redirect('../views/all_clients.php');
    } else {
        // Elimina el cliente si no tiene ventas
        $sql = "DELETE FROM clients WHERE id = '{$client_id}'";
        if ($db->query($sql)) {
            $session->msg("s", "Cliente eliminado con éxito.");
        } else {
            $session->msg("d", "Error al eliminar el cliente.");
        }
        redirect('../views/all_clients.php');
    }
} else {
    $session->msg("d", "ID de cliente faltante.");
    redirect('../views/all_clients.php');
}
?>
