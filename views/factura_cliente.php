<?php
$page_title = 'Generar Factura de Cliente';
require_once('../Model/load.php');
page_require_level(3);

// Obtener solo los clientes con estado "Activo"
$clientes = find_by_sql("SELECT * FROM clients WHERE status = 'Activo'");
$productos = [];
$cliente = [];

// Verificar si se seleccionó un cliente
if (isset($_GET['cliente_id']) && !empty($_GET['cliente_id'])) {
    $cliente_id = (int)$_GET['cliente_id'];

    // Validar que el cliente exista
    $sql_cliente = "
        SELECT 
            clients.name AS cliente_nombre,
            clients.dni AS cliente_dni,
            clients.address AS cliente_direccion
        FROM clients
        WHERE clients.id = '{$cliente_id}' AND clients.status = 'Activo'
    ";
    $resultado_cliente = find_by_sql($sql_cliente);

    if (!$resultado_cliente) {
        $session->msg("d", "Cliente no encontrado o inactivo.");
        redirect('factura_cliente.php', false);
    }

    $cliente = $resultado_cliente[0];

    // Obtener los productos asociados al cliente a través de la tabla de ventas
    $sql_productos = "
        SELECT 
            products.id AS producto_id,
            products.name AS producto_nombre,
            products.sale_price AS producto_precio_unitario,
            sales.qty AS producto_cantidad
        FROM sales
        JOIN products ON sales.product_id = products.id
        WHERE sales.client_id = '{$cliente_id}'
    ";
    $productos = find_by_sql($sql_productos);

    // Preparar los datos para enviar a la página de impresión
    $factura_data = [
        'cliente' => [
            'cliente_nombre' => $cliente['cliente_nombre'],
            'cliente_dni' => $cliente['cliente_dni'],
            'cliente_direccion' => $cliente['cliente_direccion']
        ],
        'productos' => array_map(function ($producto) {
            return [
                'codigo' => $producto['producto_id'],
                'descripcion' => $producto['producto_nombre'],
                'precio_unitario' => $producto['producto_precio_unitario'],
                'cantidad' => $producto['producto_cantidad']
            ];
        }, $productos),
        'fecha' => date('Y-m-d')
    ];

    // Codificar los datos en base64 para pasarlos por la URL
    $factura_data_encoded = base64_encode(json_encode($factura_data));
    redirect("imprimir_facturaCli.html?data={$factura_data_encoded}", false);
}
?>

<?php include_once('../layouts/header.php'); ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Seleccionar Cliente</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="get" action="factura_cliente.php" target="_blank">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select class="form-control" name="cliente_id" id="cliente_id">
                        <option value="">Seleccione un cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo (int)$cliente['id']; ?>">
                                    <?php echo htmlspecialchars($cliente['name']); ?>
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
