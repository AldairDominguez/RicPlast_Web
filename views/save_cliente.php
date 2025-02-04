<?php
require_once('../Model/load.php');
page_require_level(2); // Ajusta el nivel de acceso según corresponda

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $dni = $_POST['dni'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Validación del lado del servidor
    if (empty($name) || empty($lastname) || empty($gender) || empty($dni) || empty($address) || empty($email)) {
        $session->msg("d", "Todos los campos son obligatorios.");
        redirect('add_cliente.php', false);
    } elseif (!preg_match('/^\d{8}$/', $dni)) {
        $session->msg("d", "El DNI debe contener exactamente 8 dígitos numéricos.");
        redirect('add_cliente.php', false);
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $session->msg("d", "El email debe tener un formato válido con '@'.");
        redirect('add_cliente.php', false);
    } else {
        // Guardar el cliente en la base de datos
        $query = "INSERT INTO clients (name, lastname, gender, dni, address, email) 
                  VALUES ('{$name}', '{$lastname}', '{$gender}', '{$dni}', '{$address}', '{$email}')";
        if ($db->query($query)) {
            $session->msg("s", "Cliente agregado correctamente.");
            redirect('add_cliente.php', false);
        } else {
            $session->msg("d", "Error al agregar el cliente.");
            redirect('add_cliente.php', false);
        }
    }
} else {
    redirect('add_cliente.php');
}
?>
