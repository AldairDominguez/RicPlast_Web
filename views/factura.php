<?php
$page_title = 'Generar Factura';
require_once('../Model/load.php');
page_require_level(3);

$proveedores = find_all('suppliers'); // Obtener todos los proveedores
$productos = [];
$proveedor = [];

// Verificar si se seleccionó un proveedor
if (isset($_GET['proveedor_id']) && !empty($_GET['proveedor_id'])) {
    $proveedor_id = (int)$_GET['proveedor_id'];

    // Validar que el proveedor exista
    $sql_proveedor = "
        SELECT 
            suppliers.name AS proveedor_nombre,
            suppliers.ruc AS proveedor_ruc,
            suppliers.address AS proveedor_direccion
        FROM suppliers
        WHERE suppliers.id = '{$proveedor_id}'
    ";
    $resultado_proveedor = find_by_sql($sql_proveedor);

    if (!$resultado_proveedor) {
        $session->msg("d", "Proveedor no encontrado.");
        redirect('factura.php', false);
    }

    $proveedor = $resultado_proveedor[0];

    // Obtener los productos asociados al proveedor
    $sql_productos = "
    SELECT 
        products.id AS producto_id,
        products.name AS producto_nombre,
        products.sale_price AS producto_precio_unitario,
        products.quantity AS producto_stock,
        categories.name AS categoria_nombre
    FROM products
    JOIN categories ON products.categorie_id = categories.id
    WHERE products.supplier_id = '{$proveedor_id}'
";
    $productos = find_by_sql($sql_productos);

    // Preparar los datos para enviar a la página de impresión
    $data = [
        'proveedor' => $proveedor,
        'productos' => $productos,
        'fecha' => date("d/m/Y"),
    ];
    $encoded_data = base64_encode(json_encode($data));
    header("Location: imprimir_factura.html?data={$encoded_data}");
    exit;
}

include_once('../layouts/header.php');
?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Seleccionar Proveedor</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="GET" action="factura.php" target="_blank">
                    <!-- El atributo target="_blank" abre en una nueva pestaña -->
                    <div class="form-group">
                        <label for="proveedor_id">Proveedor</label>
                        <select class="form-control" name="proveedor_id" id="proveedor_id" required>
                            <option value="">Seleccione un proveedor</option>
                            <?php foreach ($proveedores as $prov): ?>
                                <option value="<?php echo $prov['id']; ?>">
                                    <?php echo $prov['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Generar Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('../layouts/footer.php'); ?>