<?php
require_once('../Model/load.php');
page_require_level(3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ruc = $_POST['ruc'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Validar campos requeridos
    if (empty($name) || empty($ruc) || empty($address)) {
        $session->msg("d", "Nombre, RUC y Dirección son obligatorios.");
        redirect('add_supplier.php', false);
    } elseif (!preg_match('/^\d{11}$/', $ruc)) {
        $session->msg("d", "El RUC debe contener exactamente 11 dígitos.");
        redirect('add_supplier.php', false);
    } else {
        // Verificar si el RUC ya existe
        $sql = "SELECT * FROM suppliers WHERE ruc = '{$ruc}' LIMIT 1";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            $session->msg("d", "El RUC ingresado ya está registrado en el sistema.");
            redirect('add_supplier.php', false);
        } else {
            // Insertar nuevo proveedor
            $query = "INSERT INTO suppliers (name, ruc, address, email, phone) VALUES ('{$name}', '{$ruc}', '{$address}', '{$email}', '{$phone}')";
            if ($db->query($query)) {
                $session->msg("s", "Proveedor agregado correctamente.");
                redirect('add_supplier.php', false);
            } else {
                $session->msg("d", "Error al agregar el proveedor.");
                redirect('add_supplier.php', false);
            }
        }
    }
} else {
    redirect('add_supplier.php');
}
?>
