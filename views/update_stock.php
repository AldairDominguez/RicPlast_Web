<?php
require_once('../Model/load.php');
page_require_level(2);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity_added = (int)$_POST['quantity_added'];

    // Verificar que la cantidad no sea 0 ni negativa
    if ($quantity_added === 0) {
        $session->msg("d", "La cantidad no puede ser 0.");
        redirect('products_by_supplier.php');
    }

    // Verificar si el producto existe
    $product = find_by_id('products', $product_id);
    if (!$product) {
        $session->msg("d", "Producto no encontrado.");
        redirect('products_by_supplier.php');
    }

    // Obtener el stock actual
    $current_stock = (int)$product['quantity'];

    // Calcular el nuevo stock
    $new_stock = $current_stock + $quantity_added;

    // Verificar que no se pueda reducir más stock del disponible
    if ($new_stock < 0) {
        $session->msg("d", "No puedes reducir más stock del disponible.");
        redirect('products_by_supplier.php');
    }

    // Actualizar el stock en la tabla 'products'
    $update_stock_query = "UPDATE products SET quantity = '{$new_stock}' WHERE id = '{$product_id}'";
    if ($db->query($update_stock_query)) {
        // Registrar el movimiento en la tabla 'stock_movements'
        $insert_movement_query = "
            INSERT INTO stock_movements (product_id, quantity_added, previous_stock, new_stock, timestamp)
            VALUES ('{$product_id}', '{$quantity_added}', '{$current_stock}', '{$new_stock}', NOW())
        ";
        if ($db->query($insert_movement_query)) {
            $session->msg("s", "Stock actualizado correctamente. Movimiento registrado.");
        } else {
            $session->msg("d", "Error al registrar el movimiento de stock.");
        }
    } else {
        $session->msg("d", "Error al actualizar el stock.");
    }
    redirect('products_by_supplier.php');
} else {
    redirect('products_by_supplier.php');
}
