<?php
$page_title = 'Add Sale';
require_once('../Model/load.php');
page_require_level(3);

// Obtener todos los clientes activos
$all_clients = find_by_sql("SELECT id, name FROM clients WHERE status = 'Activo'");


if (isset($_POST['add_sale'])) {
    $errors = [];
    date_default_timezone_set('America/Lima');

    // Verificar que 'client_id' exista y sea válido
    if (!isset($_POST['client_id']) || empty($_POST['client_id']) || (int)$_POST['client_id'] === 0) {
        $errors[] = "Debe seleccionar un cliente válido antes de guardar la venta." ;
    } else {
        $client_id = $db->escape((int)$_POST['client_id']);
    }

    validate_sales_fields($_POST['sales']);

    if (empty($errors)) {
        foreach ($_POST['sales'] as $sale) {
            $p_id = $db->escape((int)$sale['product_id']);
            $s_qty = $db->escape((int)$sale['quantity']);
            $s_total = $db->escape($sale['total']);

            // Obtener la fecha y hora actual para registrar
            $s_date = date("Y-m-d H:i:s");

            // Obtener información del producto
            $product = find_by_id('products', $p_id);
            if ($product['quantity'] < $s_qty) {
                $errors[] = "Stock insuficiente para el producto: " . $product['name'];
                continue;
            }

            $previous_stock = (int)$product['quantity'];
            $current_stock = $previous_stock - $s_qty;

            $update_stock_sql = "UPDATE products SET quantity = '{$current_stock}' WHERE id = '{$p_id}'";
            if (!$db->query($update_stock_sql)) {
                $errors[] = "Error al actualizar el stock del producto con ID {$p_id}";
                continue;
            }

            $insert_sale_sql = "
                INSERT INTO sales (product_id, qty, price, date, client_id, previous_stock, current_stock) 
                VALUES ('{$p_id}', '{$s_qty}', '{$s_total}', '{$s_date}', '{$client_id}', '{$previous_stock}', '{$current_stock}')
            ";
            if (!$db->query($insert_sale_sql)) {
                $errors[] = "Error al guardar la venta para el producto con ID {$p_id}";
                continue;
            }
        }

        if (empty($errors)) {
            $session->msg('s', "Venta agregada exitosamente.");
            redirect('/RicPlast3.1/Controller/add_sale.php', false);
        } else {
            $session->msg('d', implode(", ", $errors));
            redirect('/RicPlast3.1/Controller/add_sale.php', false);
        }
    } else {
        $session->msg("d", implode(", ", $errors));
        redirect('/RicPlast3.1/Controller/add_sale.php', false);
    }
}
?>

<?php include_once('../layouts/header.php'); ?>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
        <form method="post" id="sug-form">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" id="find-product">Encuéntralo</button>
                    </span>
                    <input type="text" id="sug_input" class="form-control" name="title" placeholder="Buscar por nombre de producto">
                </div>
                <div id="result" class="list-group"></div>
            </div>
            <div class="form-group">
                <label>Stock disponible: <span id="stock-display">N/A</span></label>
            </div>
        </form>
    </div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Registrar Venta</span>
                </strong>
            </div>
            <div class="panel-body" style="background-color:#D9D1D0;">
                <form method="post" action="/RicPlast3.1/Controller/add_sale.php" onsubmit="return validateForm();">
                    <div class="form-group">
                        <label for="client_id">Seleccionar Cliente</label>
                        <select class="form-control" name="client_id" id="client_id" onchange="toggleSaveButton();">
                            <option value="">Seleccione un cliente</option>
                            <?php foreach ($all_clients as $client): ?>
                                <option value="<?php echo $client['id']; ?>"><?php echo $client['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <th>Artículo</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Cant.</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </thead>
                        <tbody id="product_info">
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary" name="add_sale" id="add-sale-button" disabled>Guardar Venta</button>
                </form>
            </div>
        </div>
        <button type="button" class="btn btn-success" onclick="location.href='/RicPlast3.1/views/Client_report.php'">Imprimir boleta de cliente</button>
        <button type="button" class="btn btn-success" onclick="location.href='/RicPlast3.1/views/factura_cliente.php'">Factura de cliente</button>
    </div>
</div>

<script>
document.getElementById("sug_input").addEventListener("input", function() {
    let title = this.value;
    if (title.length > 0) {
        fetch('ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'product_name=' + encodeURIComponent(title)
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("result").innerHTML = data;
            document.getElementById("result").style.display = "block";
        });
    } else {
        document.getElementById("result").innerHTML = '';
        document.getElementById("result").style.display = "none";
        document.getElementById("stock-display").textContent = "N/A";
        document.getElementById("stock-display").style.color = "black";
    }
});

function buscarCliente() {
    var nombre = document.getElementById("buscar_cliente").value;
    if (nombre.length > 2) {
        fetch('buscar_cliente.php?nombre=' + encodeURIComponent(nombre))
        .then(response => response.text())
        .then(data => {
            document.getElementById("sugerencias_cliente").innerHTML = data;
            document.getElementById("sugerencias_cliente").style.display = "block";
        });
    } else {
        document.getElementById("sugerencias_cliente").innerHTML = "";
        document.getElementById("sugerencias_cliente").style.display = "none";
    }
}

function seleccionarCliente(id, nombre) {
    document.getElementById("buscar_cliente").value = nombre;
    document.getElementById("client_id").value = id; // Actualiza el valor del select
    document.getElementById("client_id_hidden").value = id;
    document.getElementById("sugerencias_cliente").innerHTML = "";
    document.getElementById("sugerencias_cliente").style.display = "none";
    toggleSaveButton();
}

let productIndex = 0;

function selectProduct(id, name, price, brand, category) {
    document.getElementById("result").style.display = "none";
    document.getElementById("sug_input").value = name;

    fetch('get_stock.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + encodeURIComponent(id)
    })
    .then(response => response.text())
    .then(stock => {
        stock = parseInt(stock);
        const stockDisplay = document.getElementById("stock-display");

        if (stock <= 0) {
            stockDisplay.textContent = "No disponible";
            stockDisplay.style.color = "red";
            return;
        } else {
            stockDisplay.textContent = stock;
            stockDisplay.style.color = "black";
        }

        const existingRow = Array.from(document.querySelectorAll('#product_info tr')).find(row => 
            row.querySelector('input[name^="sales"][name$="[product_id]"]').value == id
        );

        if (existingRow) {
            alert("El producto ya está en la tabla.");
            return;
        }

        let productInfo = document.getElementById("product_info");
        let row = document.createElement("tr");

        row.innerHTML = `
            <td><input type="hidden" name="sales[${productIndex}][product_id]" value="${id}">${name}</td>
            <td>${brand}</td>
            <td>${category}</td>
            <td><input type="text" name="sales[${productIndex}][price]" value="${parseFloat(price).toFixed(2)}" readonly></td>
            <td><input type="number" name="sales[${productIndex}][quantity]" value="1" min="1" max="${stock}" oninput="updateTotal(this, ${stock})"></td>
            <td><input type="text" name="sales[${productIndex}][total]" value="${parseFloat(price).toFixed(2)}" readonly></td>
            <td><input type="text" name="sales[${productIndex}][date]" value="${formatDate(new Date())}" readonly></td>
            <td><button type="button" class="btn btn-danger remove-product">Eliminar</button></td>
        `;

        productInfo.appendChild(row);
        toggleSaveButton();
        productIndex++;

        row.querySelector('.remove-product').addEventListener('click', function() {
            row.remove();
            toggleSaveButton();
        });
    });
}

function updateTotal(element, stock) {
    let row = element.closest('tr');
    let price = parseFloat(row.querySelector(`input[name^="sales"][name$="[price]"]`).value);
    let quantity = parseInt(element.value) || 1;

    if (quantity > stock) {
        alert("Cantidad de producto no disponible. Solo hay " + stock + " en stock.");
        element.value = stock;
        quantity = stock;
    }

    let totalField = row.querySelector(`input[name^="sales"][name$="[total]"]`);
    totalField.value = (price * quantity).toFixed(2);
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function toggleSaveButton() {
    const productInfo = document.getElementById("product_info");
    const addSaleButton = document.getElementById("add-sale-button");
    const clientId = document.getElementById("client_id").value;

    // Validar si hay productos en la tabla y un cliente seleccionado
    if (productInfo.children.length > 0 && clientId && clientId !== "") {
        addSaleButton.disabled = false;
    } else {
        addSaleButton.disabled = true;
    }
}

function validateForm() {
    const productInfo = document.getElementById("product_info");
    if (productInfo.children.length === 0) {
        alert("Debe agregar al menos un producto antes de guardar la venta.");
        return false;
    }
    if (!document.getElementById("client_id").value) {
        alert("Debe seleccionar un cliente antes de guardar la venta.");
        return false;
    }

    let isValid = true;
    document.querySelectorAll("#product_info tr").forEach(row => {
        const productId = row.querySelector('input[name="sales[][product_id]"]').value;
        const quantity = row.querySelector('input[name="sales[][quantity]"]').value;
        const price = row.querySelector('input[name="sales[][price]"]').value;
        const date = row.querySelector('input[name="sales[][date]"]').value;

        if (!productId || !quantity || !price || !date) {
            isValid = false;
            alert("Existen campos vacíos en los productos seleccionados.");
        }
    });

    return isValid;
}
</script>

<?php include_once('../layouts/footer.php'); ?>
