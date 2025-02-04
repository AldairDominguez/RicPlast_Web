<?php
require_once('../Model/load.php');
page_require_level(3); // Ajusta el nivel de acceso según sea necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)$_POST['id'];
    $status = $_POST['status'];

    // Validar entrada
    if (empty($client_id) || !in_array($status, ['Activo', 'Inactivo'])) {
        $session->msg("d", "Datos inválidos.");
        redirect('all_clients.php');
    }

    // Actualizar estado en la base de datos
    $sql = "UPDATE clients SET status = '{$status}' WHERE id = '{$client_id}'";
    if ($db->query($sql)) {
        $session->msg("s", "Estado del cliente actualizado correctamente.");
    } else {
        $session->msg("d", "No se pudo actualizar el estado del cliente.");
    }

    redirect('all_clients.php');
} else {
    redirect('all_clients.php');
}
