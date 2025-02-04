<?php
$page_title = 'Agregar Proveedor';
require_once('../Model/load.php');
page_require_level(3); // Asegúrate de ajustar el nivel de acceso
?>
<?php include_once('../layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>Agregar Nuevo Proveedor</span>
                </strong>
            </div>
            <div class="panel-body">
                <?php echo display_msg($msg); ?>
                <form method="post" action="save_supplier.php" onsubmit="appendRucPrefix()">
                    <div class="form-group">
                        <label for="name">Nombre de la Empresa</label>
                        <input type="text" class="form-control" name="name" placeholder="Nombre de la Empresa" required>
                    </div>
                    <div class="form-group">
                        <label for="ruc_type">Tipo de RUC</label>
                        <select id="ruc_type" class="form-control" required>
                            <option value="10">Persona Natural (10)</option>
                            <option value="20">Persona Jurídica (20)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ruc">RUC</label>
                        <div class="input-group">
                            <span class="input-group-addon" id="ruc_prefix">10</span>
                            <input type="text" class="form-control" id="ruc" name="ruc" placeholder="Ingrese los 9 dígitos restantes" pattern="\d{9}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" name="address" placeholder="Dirección" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control" name="phone" placeholder="Teléfono">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Proveedor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Actualiza el prefijo del RUC según el tipo seleccionado
    document.getElementById('ruc_type').addEventListener('change', function() {
        const prefix = this.value;
        document.getElementById('ruc_prefix').textContent = prefix;
    });

    // Restringe el campo RUC a 9 dígitos
    const rucInput = document.getElementById('ruc');
    rucInput.addEventListener('input', function() {
        // Permite solo números y corta a 9 caracteres
        this.value = this.value.replace(/\D/g, '').slice(0, 9);
    });

    // Función para concatenar el prefijo al RUC antes de enviar el formulario
    function appendRucPrefix() {
        const prefix = document.getElementById('ruc_type').value;
        const rucField = document.getElementById('ruc');
        rucField.value = prefix + rucField.value;
    }
</script>

<?php include_once('../layouts/footer.php'); ?>
