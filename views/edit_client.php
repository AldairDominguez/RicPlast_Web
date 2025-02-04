<?php
$page_title = 'Editar Cliente';
require_once('../Model/load.php');
page_require_level(3); // Cambia el nivel de acceso según sea necesario

// Verifica si el ID del cliente ha sido pasado a través de la URL
if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];
    $client = find_by_id('clients', $client_id);

    if (!$client) {
        $session->msg("d", "ID de cliente no encontrado.");
        redirect('clients.php');
    }
} else {
    $session->msg("d", "ID de cliente faltante.");
    redirect('clients.php');
}

// Procesa la solicitud de actualización del cliente
if (isset($_POST['update_client'])) {
    $req_fields = ['name', 'lastname', 'gender', 'dni', 'address', 'email'];
    validate_fields($req_fields);

    if (empty($errors)) {
        $name = remove_junk($db->escape($_POST['name']));
        $lastname = remove_junk($db->escape($_POST['lastname']));
        $gender = remove_junk($db->escape($_POST['gender']));
        $dni = remove_junk($db->escape($_POST['dni']));
        $address = remove_junk($db->escape($_POST['address']));
        $email = remove_junk($db->escape($_POST['email']));

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $lastname)) {
            $session->msg("d", "El apellido solo puede contener letras.");
            redirect('edit_client.php?id=' . $client_id, false);
        }

        $sql = "UPDATE clients SET name='{$name}', lastname='{$lastname}', gender='{$gender}', dni='{$dni}', address='{$address}', email='{$email}' WHERE id='{$client_id}'";
        if ($db->query($sql)) {
            $session->msg("s", "Cliente actualizado con éxito.");
            redirect('../views/all_clients.php', false);
        } else {
            $session->msg("d", "Error al actualizar el cliente.");
            redirect('edit_client.php?id=' . $client_id, false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_client.php?id=' . $client_id, false);
    }
}

?>

<?php include_once('../layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Editar Cliente</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_client.php?id=<?php echo (int)$client['id']; ?>">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" value="<?php echo remove_junk($client['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Apellido</label>
                        <input type="text" class="form-control" name="lastname" value="<?php echo remove_junk($client['lastname']); ?>" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="El apellido solo puede contener letras." required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Género</label>
                        <select class="form-control" name="gender" required>
                            <option value="Masculino" <?php echo ($client['gender'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo ($client['gender'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" class="form-control" name="dni" value="<?php echo remove_junk($client['dni']); ?>" pattern="\d{8}" maxlength="8" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" name="address" value="<?php echo remove_junk($client['address']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo remove_junk($client['email']); ?>" required>
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" name="update_client" class="btn btn-primary">Actualizar</button>
                        <a href="all_clients.php" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('../layouts/footer.php'); ?>
