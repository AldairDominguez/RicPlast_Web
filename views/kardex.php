<?php
$page_title = 'Kardex por Producto - Entradas y Salidas';
require_once('../Model/load.php');
page_require_level(3);

// Establecer el idioma en español para la fecha
setlocale(LC_TIME, 'es_ES.UTF-8');

// Verificar si se ha enviado el formulario
if (isset($_POST['generate_report'])) {
    $product_id = (int) $_POST['product_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validaciones
    if (empty($product_id)) {
        $session->msg("d", "Producto no especificado.");
        redirect('kardex.php', false);
    }

    if (empty($start_date) || empty($end_date)) {
        $session->msg("d", "Debe especificar el rango de fechas.");
        redirect('kardex.php', false);
    }

    // Obtener datos iniciales del producto
    $initial_stock_query = "SELECT quantity, name FROM products WHERE id = '{$product_id}'";
    $product_data = find_by_sql($initial_stock_query);

    if (!$product_data) {
        $session->msg("d", "Producto no encontrado.");
        redirect('kardex.php', false);
    }

    $initial_stock = (int) ($product_data[0]['quantity'] ?? 0);
    $product_name = $product_data[0]['name'] ?? 'Producto Desconocido';

    // Consulta para obtener movimientos (entradas y salidas)
    $sql = "
    SELECT 
        'Entrada' AS Tipo_Movimiento, 
        DATE(sm.timestamp) AS Fecha_Movimiento, -- Mostrar solo la fecha
        TIME(sm.timestamp) AS Hora_Movimiento, -- Añadir hora para el ordenamiento
        CONCAT('+', sm.quantity_added) AS Cantidad, 
        sm.previous_stock AS Stock_Anterior, 
        sm.new_stock AS Stock_Actual, 
        suppliers.name AS Proveedor, 
        suppliers.ruc AS Documento, -- RUC del proveedor
        '' AS Cliente, -- No aplica para entradas
        p.buy_price AS Precio, -- Precio de compra
        (sm.quantity_added * p.buy_price) AS Total -- Total calculado
    FROM stock_movements sm
    JOIN products p ON sm.product_id = p.id
    LEFT JOIN suppliers ON p.supplier_id = suppliers.id
    WHERE sm.product_id = '{$product_id}' 
    AND DATE(sm.timestamp) BETWEEN '{$start_date}' AND '{$end_date}'

    UNION ALL

    SELECT 
        'Salida' AS Tipo_Movimiento, 
        DATE(s.date) AS Fecha_Movimiento, -- Mostrar solo la fecha
        TIME(s.date) AS Hora_Movimiento, -- Añadir hora para el ordenamiento
        CONCAT('-', s.qty) AS Cantidad, 
        s.previous_stock AS Stock_Anterior, 
        s.current_stock AS Stock_Actual, 
        '' AS Proveedor, -- No aplica para salidas
        c.dni AS Documento, -- DNI del cliente
        c.name AS Cliente, -- Nombre del cliente
        p.sale_price AS Precio, -- Precio unitario del producto
        (s.qty * p.sale_price) AS Total -- Total calculado
    FROM sales s
    JOIN products p ON s.product_id = p.id
    LEFT JOIN clients c ON s.client_id = c.id
    WHERE s.product_id = '{$product_id}' 
    AND DATE(s.date) BETWEEN '{$start_date}' AND '{$end_date}'

    ORDER BY Fecha_Movimiento ASC, Hora_Movimiento ASC"; 
    // Ordenar por fecha y hora

    $movements = find_by_sql($sql);

    // Convertir los datos a formato JSON para imprimir
    $json_data = json_encode([
        "movements" => $movements,
        "nombre_producto" => $product_name,
        "fecha_inicio" => $start_date,
        "fecha_fin" => $end_date,
        "stock_actual" => $initial_stock
    ]);
    echo "<script>
            localStorage.setItem('json_data', '" . addslashes($json_data) . "');
          </script>";
}
?>

<?php include_once('../layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<!-- Formulario de filtros -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Kardex por Producto - Entradas y Salidas</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="kardex.php">
                    <div class="form-group">
                        <label for="product_id">Seleccionar Producto</label>
                        <select class="form-control" name="product_id" required>
                            <option value="">Seleccione un producto</option>
                            <?php
                            $products = find_all('products');
                            foreach ($products as $product) : ?>
                                <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Desde</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Hasta</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                    <button type="submit" name="generate_report" class="btn btn-primary">Generar informe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de resultados -->
<?php if (isset($movements) && count($movements) > 0) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Reporte de Kardex - Entradas y Salidas</strong>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                    <thead>
    <tr>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Proveedor/Cliente</th>
        <th>Documento</th>
        <th>Cantidad</th>
        <th>Stock Anterior</th>
        <th>Stock Actual</th>
        <th>Precio</th>
        <th>Total</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($movements as $movement) : ?>
        <tr>
            <td><?php echo $movement['Fecha_Movimiento']; ?></td> <!-- Solo se muestra la fecha -->
            <td><?php echo $movement['Tipo_Movimiento']; ?></td>
            <td><?php echo $movement['Proveedor'] ?: $movement['Cliente']; ?></td>
            <td><?php echo $movement['Documento'] ?: 'N/A'; ?></td>
            <td><?php echo $movement['Cantidad']; ?></td>
            <td><?php echo $movement['Stock_Anterior'] ?: 'N/A'; ?></td>
            <td><?php echo $movement['Stock_Actual'] ?: 'N/A'; ?></td>
            <td><?php echo $movement['Precio'] ? number_format($movement['Precio'], 2) : 'N/A'; ?></td>
            <td><?php echo $movement['Total'] ? number_format($movement['Total'], 2) : 'N/A'; ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button onclick="imprimirReporte()" class="btn btn-info">Imprimir Reporte</button>
        </div>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                No se encontraron movimientos para el producto seleccionado en el rango de fechas especificado.
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function imprimirReporte() {
        const jsonData = {
            movements: <?php echo json_encode($movements); ?>,
            fecha_inicio: "<?php echo $start_date ?? ''; ?>",
            fecha_fin: "<?php echo $end_date ?? ''; ?>",
            nombre_producto: "<?php echo $product_name ?? 'Producto Desconocido'; ?>"
        };

        localStorage.setItem('json_data', JSON.stringify(jsonData));
        window.open('report_Kardex.html', '_blank');
    }
</script>

<?php include_once('../layouts/footer.php'); ?>
