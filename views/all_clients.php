<?php
$page_title = 'Lista de Clientes';
require_once('../Model/load.php');
page_require_level(3); // Cambia el nivel de acceso según sea necesario

// Obtener todos los clientes
$all_clients = find_all('clients');

// Función para verificar si un cliente tiene ventas
function has_sales($client_id) {
    global $db;
    $sql = "SELECT COUNT(*) AS total FROM sales WHERE client_id = '{$client_id}'";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'] > 0;
}

?>

<?php include_once('../layouts/header.php'); ?>

<style>
    /* Estilo para la fila inactiva */
    .inactive-row {
        background-color: #f2f2f2;
        color: #999;
    }

    .inactive-row a {
        pointer-events: none;
        cursor: not-allowed;
    }

    /* Estilo para el combo box según estado */
    .status-select {
        color: #fff;
        background-color: #28a745; /* Verde por defecto */
        border: none;
        text-align: center;
    }

    .status-select.inactive {
        background-color: #dc3545; /* Rojo para inactivo */
        border: none;
    }

    /* Mantener el color de fondo al desplegar o enfocar el combo box */
    .status-select:focus,
    .status-select:active,
    .status-select option:checked {
        color: #fff;
        background-color: #6c757d; /* Gris oscuro para el fondo desplegado */
        outline: none; /* Quitar el borde azul del enfoque */
    }

    /* Cambiar el color de las opciones al pasar el cursor */
    .status-select option:hover {
        background-color: #495057; /* Gris más oscuro */
        color: #fff; /* Letras blancas */
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Lista de Clientes</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Género</th>
                            <th>DNI</th>
                            <th>Dirección</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_clients as $client): ?>
                            <tr class="<?php echo ($client['status'] == 'Inactivo') ? 'inactive-row' : ''; ?>">
                                <td><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk($client['name']); ?></td>
                                <td><?php echo remove_junk($client['lastname']); ?></td>
                                <td><?php echo remove_junk($client['gender']); ?></td>
                                <td><?php echo remove_junk($client['dni']); ?></td>
                                <td><?php echo remove_junk($client['address']); ?></td>
                                <td><?php echo remove_junk($client['email']); ?></td>
                                <td>
                                    <form method="post" action="update_statusClient.php">
                                        <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
                                        <select name="status" class="form-control status-select <?php echo ($client['status'] == 'Inactivo') ? 'inactive' : ''; ?>" onchange="this.form.submit()">
                                            <option value="Activo" <?php echo ($client['status'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                                            <option value="Inactivo" <?php echo ($client['status'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="edit_client.php?id=<?php echo (int)$client['id'];?>" class="btn btn-warning btn-sm <?php echo ($client['status'] == 'Inactivo') ? 'disabled' : ''; ?>" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <?php if (has_sales($client['id'])): ?>
                                        <!-- Mostrar mensaje si el cliente tiene ventas y no puede ser eliminado -->
                                        <button class="btn btn-danger btn-sm" title="No se puede eliminar" data-toggle="tooltip" onclick="alert('No se puede eliminar este cliente porque tiene ventas asociadas.');" <?php echo ($client['status'] == 'Inactivo') ? 'disabled' : ''; ?>>
                                        <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
                                        </button>
                                    <?php else: ?>
                                        <!-- Permitir eliminación si no tiene ventas -->
                                        <a href="../views/delete_client.php?id=<?php echo (int)$client['id'];?>" class="btn btn-danger btn-sm <?php echo ($client['status'] == 'Inactivo') ? 'disabled' : ''; ?>" title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Está seguro de que desea eliminar este cliente?');">
                                        <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../layouts/footer.php'); ?>
