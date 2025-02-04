<?php
$page_title = 'Editar Proveedor';
require_once('../Model/load.php');
page_require_level(3);

$id = (int)$_GET['id'];
$supplier = find_by_id('suppliers', $id);
if (!$supplier) {
    $session->msg("d", "Proveedor no encontrado.");
    redirect('all_suppliers.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ruc = $_POST['ruc'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($ruc) || empty($address)) {
        $session->msg("d", "Nombre, RUC y Dirección son obligatorios.");
        redirect('edit_supplier.php?id=' . $id);
    } elseif (!preg_match('/^\d{11}$/', $ruc)) {
        $session->msg("d", "El RUC debe contener exactamente 11 dígitos.");
        redirect('edit_supplier.php?id=' . $id);
    } else {
        $query = "UPDATE suppliers SET name = '{$name}', ruc = '{$ruc}', address = '{$address}', email = '{$email}', phone = '{$phone}' WHERE id = {$id}";
        if ($db->query($query)) {
            $session->msg("s", "Proveedor actualizado correctamente.");
            redirect('all_suppliers.php');
        } else {
            $session->msg("d", "Error al actualizar el proveedor.");
            redirect('edit_supplier.php?id=' . $id);
        }
    }
}
?>
<?php include_once('../layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-edit"></span>
                    <span>Editar Proveedor</span>
                </strong>
            </div>
            <div class="panel-body">
                <?php echo display_msg($msg); ?>
                <form method="post" action="edit_supplier.php?id=<?php echo $supplier['id']; ?>" onsubmit="appendRucPrefix()">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $supplier['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ruc_type">Tipo de RUC</label>
                        <select id="ruc_type" class="form-control" required>
                            <option value="10" <?php echo (substr($supplier['ruc'], 0, 2) == '10') ? 'selected' : ''; ?>>Persona Natural (10)</option>
                            <option value="20" <?php echo (substr($supplier['ruc'], 0, 2) == '20') ? 'selected' : ''; ?>>Persona Jurídica (20)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ruc">RUC</label>
                        <div class="input-group">
                            <span class="input-group-addon" id="ruc_prefix"><?php echo substr($supplier['ruc'], 0, 2); ?></span>
                            <input type="text" class="form-control" id="ruc" name="ruc" value="<?php echo substr($supplier['ruc'], 2); ?>" pattern="\d{9}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" name="address" value="<?php echo $supplier['address']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $supplier['email']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo $supplier['phone']; ?>">
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
                        <a href="all_suppliers.php" class="btn btn-default">Cancelar</a>
                    </div>
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
