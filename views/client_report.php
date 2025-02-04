<?php
$page_title = 'Reporte de Compras por Cliente';
require_once('../Model/load.php');
page_require_level(3);

// Obtener solo los clientes activos
$clientes = find_by_sql("SELECT id, name FROM clients WHERE status = 'Activo'");
?>

<?php include_once('../layouts/header.php'); ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Buscar Cliente</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="client_report.php">
                    <div class="form-group">
                        <label for="client_id">Cliente</label>
                        <select class="form-control" name="client_id" id="client_id" required>
                            <option value="">Seleccione un cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo htmlspecialchars($cliente['id'], ENT_QUOTES); ?>">
                                    <?php echo htmlspecialchars($cliente['name'], ENT_QUOTES); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Generar Reporte</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['client_id'])) {
    $client_id = (int)$_POST['client_id'];
    
    // Obtener el nombre, DNI y dirección del cliente
    $client_data = find_by_id('clients', $client_id);
    if (!$client_data || $client_data['status'] !== 'Activo') {
        echo "<div class='alert alert-danger'>Cliente no encontrado o inactivo.</div>";
        include_once('../layouts/footer.php');
        exit;
    }

    $client_name = htmlspecialchars($client_data['name'], ENT_QUOTES);
    $client_dni = htmlspecialchars($client_data['dni'], ENT_QUOTES);
    $client_address = htmlspecialchars($client_data['address'], ENT_QUOTES); // Dirección del cliente

    $sql = "SELECT sales.date, products.name AS product_name, sales.qty, products.sale_price AS price, (sales.qty * products.sale_price) AS total 
            FROM sales 
            JOIN products ON sales.product_id = products.id 
            WHERE sales.client_id = '{$client_id}' 
            ORDER BY sales.date DESC";
    
    $purchases = array_map(function ($purchase) {
        return array_map('htmlspecialchars', $purchase);
    }, find_by_sql($sql));
    
    $total_sum = 0;
    foreach ($purchases as $purchase) {
        $total_sum += $purchase['total'];
    }

    // Convertir las variables a JSON para usarlas en JavaScript
    $client_name_json = json_encode($client_name);
    $client_dni_json = json_encode($client_dni);
    $client_address_json = json_encode($client_address); // JSON para la dirección
    $purchases_json = json_encode($purchases, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    $total_sum_json = json_encode($total_sum);
    ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Reporte de Compras para el Cliente: <?php echo $client_name; ?></strong>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchases as $purchase): ?>
                                <tr>
                                    <td><?php echo remove_junk($purchase['date']); ?></td>
                                    <td><?php echo remove_junk($purchase['product_name']); ?></td>
                                    <td><?php echo (int)$purchase['qty']; ?></td>
                                    <td><?php echo number_format($purchase['price'], 2); ?></td>
                                    <td><?php echo number_format($purchase['total'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Suma Total:</th>
                                <th><?php echo number_format($total_sum, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Botón de Imprimir Boleta -->
                <div class="text-center">
                    <button onclick="generarBoleta()" class="btn btn-success">Imprimir Boleta</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('../layouts/footer.php'); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cliente = <?php echo $client_name_json; ?>;
            const dni = <?php echo $client_dni_json; ?>;
            const direccion = <?php echo $client_address_json; ?>; // Agregar dirección
            const compras = <?php echo $purchases_json; ?>;
            const total = <?php echo $total_sum_json; ?>;

            function generarBoleta() {
                console.log("Cliente:", cliente);
                console.log("DNI:", dni);
                console.log("Dirección:", direccion);
                console.log("Compras:", compras);
                console.log("Total:", total);
                
                // Guardar datos en localStorage
                const boletaData = {
                    cliente: cliente,
                    dni: dni,
                    direccion: direccion,
                    compras: compras,
                    total: total
                };
                localStorage.setItem("boletaData", JSON.stringify(boletaData));
                
                // Redirigir a report_client.html
                window.open('report_client.html', '_blank');
            }

            window.generarBoleta = generarBoleta;
        });
    </script>
<?php
} else {
    echo "<div class='alert alert-warning'>No se encontraron compras para el cliente seleccionado.</div>";  
}
?>
<?php include_once('../layouts/footer.php'); ?>