<?php
$page_title = 'Productos por proveedor';
require_once('../Model/load.php');
page_require_level(2);

// Obtener solo los proveedores activos
$all_suppliers = find_by_sql("SELECT id, name FROM suppliers WHERE status = 'Activo'");

// Validar si se seleccionó un proveedor
$supplier_id = isset($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : null;
$product_name = isset($_GET['product_name']) ? $db->escape($_GET['product_name']) : null;
$products = [];
$supplier_name = '';

if ($supplier_id) {
    // Obtener el nombre del proveedor activo
    $query_supplier = "SELECT name FROM suppliers WHERE id = '{$supplier_id}' AND status = 'Activo' LIMIT 1";
    $supplier = find_by_sql($query_supplier);

    if (!empty($supplier)) {
        $supplier_name = $supplier[0]['name'];

        // Obtener los productos relacionados con el proveedor y filtrar por nombre
        $query_products = "
            SELECT 
                p.id, p.name, p.quantity, p.buy_price, p.sale_price, 
                c.name AS category_name, m.file_name AS photo, p.brand
            FROM products p
            LEFT JOIN categories c ON p.categorie_id = c.id
            LEFT JOIN media m ON p.media_id = m.id
            WHERE p.supplier_id = '{$supplier_id}'";

        if (!empty($product_name)) {
            $query_products .= " AND p.name LIKE '%{$product_name}%'";
        }

        $products = find_by_sql($query_products);
    } else {
        $session->msg("d", "Proveedor no encontrado o inactivo.");
        redirect('products_by_supplier.php');
    }
}
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Productos por proveedor</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="GET" action="products_by_supplier.php" class="form-inline">
                <div class="form-group">
                    <label for="supplier_id">Selecciona un proveedor:</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        <option value="">-- Selecciona un proveedor --</option>
                        <?php foreach ($all_suppliers as $supplier): ?>
                            <option value="<?php echo $supplier['id']; ?>" <?php echo ($supplier_id === $supplier['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($supplier['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Ver productos</button>
            </form>
        </div>
    </div>
</div>

<?php if ($supplier_id && !empty($supplier_name)): ?>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Lista de productos de <?php echo htmlspecialchars($supplier_name); ?></span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="GET" action="products_by_supplier.php" class="form-inline">
                    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>">
                    <div class="form-group">
                        <label for="product_name">Buscar producto:</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo htmlspecialchars($product_name); ?>" placeholder="Nombre del producto">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nombre del Producto</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>En stock</th>
                            <th>Último movimiento</th>
                            <th>Actualizar stock</th>
                            <th>Precio de compra</th>
                            <th>Precio de venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php $count = 1; ?>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td>
                                    <?php if (!empty($product['photo'])): ?>
                                        <img src="../uploads/products/<?php echo htmlspecialchars($product['photo']); ?>" width="50" height="50">
                                    <?php else: ?>
                                        <img src="../uploads/products/no_image.jpg" width="50" height="50">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['brand']); ?></td>
                                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                                <td>
                                    <?php
                                    $movements = find_by_sql("
                                        SELECT quantity_added, timestamp 
                                        FROM stock_movements 
                                        WHERE product_id = '{$product['id']}' 
                                        ORDER BY timestamp DESC 
                                        LIMIT 1
                                    ");
                                    if (!empty($movements)) {
                                        $movement = $movements[0];
                                        echo ($movement['quantity_added'] > 0 ? "+" : "") . $movement['quantity_added'] . " (" . $movement['timestamp'] . ")";
                                    } else {
                                        echo "Sin movimientos recientes.";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form method="POST" action="update_stock.php" class="form-inline" onsubmit="return validateStockInput(this);">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="number" name="quantity_added" class="form-control" placeholder="Cantidad" min="1" required>
                                        <button type="submit" class="btn btn-success">Ingresar</button>
                                    </form>
                                </td>
                                <td>S/. <?php echo number_format($product['buy_price'], 2); ?></td>
                                <td>S/. <?php echo number_format($product['sale_price'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">No se encontraron productos relacionados con este proveedor.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function validateSupplierSelection() {
        const supplierSelect = document.getElementById('supplier_id');
        if (supplierSelect.value === "") {
            alert("Por favor, selecciona un proveedor antes de continuar.");
            return false;
        }
        return true;
    }

    function validateStockInput(form) {
        const quantity = parseInt(form.quantity_added.value, 10);
        if (isNaN(quantity) || quantity <= 0) {
            alert("Por favor ingresa un valor mayor a 0.");
            return false;
        }
        return true;
    }
</script>

<?php include_once('../layouts/footer.php'); ?>
