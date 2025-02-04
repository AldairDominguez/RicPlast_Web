<?php
$page_title = 'Lista de Proveedores';
require_once('../Model/load.php');
page_require_level(3);

$suppliers = find_all('suppliers');
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
                    <span>Proveedores</span>
                </strong>
            </div>
            <div class="panel-body">
                <?php echo display_msg($msg); // Muestra mensajes de éxito o error ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre de la Empresa</th>
                            <th>RUC</th>
                            <th>Dirección</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $supplier): ?>
                            <tr class="<?php echo ($supplier['status'] == 'Inactivo') ? 'inactive-row' : ''; ?>">
                                <td><?php echo $supplier['id']; ?></td>
                                <td><?php echo $supplier['name']; ?></td>
                                <td><?php echo $supplier['ruc']; ?></td>
                                <td><?php echo $supplier['address']; ?></td>
                                <td><?php echo $supplier['email']; ?></td>
                                <td><?php echo $supplier['phone']; ?></td>
                                <td>
                                    <form method="post" action="update_status.php">
                                        <input type="hidden" name="id" value="<?php echo $supplier['id']; ?>">
                                        <select name="status" 
                                                class="form-control status-select <?php echo ($supplier['status'] == 'Inactivo') ? 'inactive' : ''; ?>" 
                                                onchange="this.form.submit()">
                                            <option value="Activo" <?php echo ($supplier['status'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                                            <option value="Inactivo" <?php echo ($supplier['status'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="edit_supplier.php?id=<?php echo $supplier['id']; ?>" 
                                       class="btn btn-warning btn-sm <?php echo ($supplier['status'] == 'Inactivo') ? 'disabled' : ''; ?>">
                                        <i class="glyphicon glyphicon-pencil"></i> Editar
                                    </a>
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
