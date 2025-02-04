<?php
$page_title = 'Agregar Cliente';
require_once('../Model/load.php');
page_require_level(3); // Asegúrate de ajustar el nivel de acceso según corresponda
?>
<?php include_once('../layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Agregar Nuevo Cliente</span>
                </strong>
            </div>
            <div class="panel-body">
                <?php echo display_msg($msg); // Muestra mensajes de sesión, como errores o confirmaciones ?>
                <form method="post" action="save_cliente.php">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" placeholder="Nombre del Cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Apellido</label>
                        <input type="text" class="form-control" name="lastname" placeholder="Apellido del Cliente" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="El apellido solo puede contener letras." required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Género</label>
                        <select class="form-control" name="gender" required>
                            <option value="">Seleccione una opción</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" class="form-control" name="dni" placeholder="DNI del Cliente" pattern="\d{8}" maxlength="8" required>
                        <small class="form-text text-muted">Debe contener exactamente 8 dígitos.</small>
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" name="address" placeholder="Dirección" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('../layouts/footer.php'); ?>
