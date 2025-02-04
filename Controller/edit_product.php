<?php
$page_title = 'Edit product';
require_once('../Model/load.php');
page_require_level(2);

$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_suppliers = find_all('suppliers'); // Obtener todos los proveedores

if (!$product) {
    $session->msg("d", "ID de producto faltante");
    redirect('../views/product.php');
}
?>
<?php
if (isset($_POST['product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-brand', 'product-quantity', 'buying-price', 'saleing-price', 'product-unit', 'product-supplier');
  validate_fields($req_fields);

  if (empty($errors)) {
      $p_name = remove_junk($db->escape($_POST['product-title']));
      $p_cat = (int)$_POST['product-categorie'];
      $p_brand = remove_junk($db->escape($_POST['product-brand']));
      $p_qty = remove_junk($db->escape($_POST['product-quantity']));

      if (!is_numeric($p_qty) || strtolower($p_qty) === 'no disponible') {
       $p_qty = 0; // Convierte "No disponible" o valores no numéricos a 0
      }

      $p_buy = remove_junk($db->escape($_POST['buying-price']));
      $p_sale = remove_junk($db->escape($_POST['saleing-price']));
      $p_unit = remove_junk($db->escape($_POST['product-unit']));
      $p_supplier = (int)$_POST['product-supplier'];

      // Verificar que el precio de venta no sea menor que el precio de compra
      if ($p_sale < $p_buy) {
          $session->msg("d", "El precio de venta no puede ser menor que el precio de compra.");
          redirect('/RicPlast3.1/Controller/edit_product.php?id=' . $product['id'], false);
      }

      if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
          $media_id = '0';
      } else {
          $media_id = remove_junk($db->escape($_POST['product-photo']));
      }

      $query = "UPDATE products SET";
      $query .= " name ='{$p_name}', brand ='{$p_brand}', quantity ='{$p_qty}', buy_price ='{$p_buy}', sale_price ='{$p_sale}',";
      $query .= " categorie_id ='{$p_cat}', media_id='{$media_id}', unidad_venta='{$p_unit}', supplier_id='{$p_supplier}'";
      $query .= " WHERE id ='{$product['id']}'";

      $result = $db->query($query);

      if ($result && $db->affected_rows() === 1) {
          $session->msg('s', "Producto actualizado");
          redirect('../views/product.php', false);
      } else {
          $session->msg('d', '¡Lo siento, no se pudo actualizar!');
          redirect('/RicPlast3.1/Controller/edit_product.php?id=' . $product['id'], false);
      }
  } else {
      $session->msg("d", $errors);
      redirect('/RicPlast3.1/Controller/edit_product.php?id=' . $product['id'], false);
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
                <span>Editar producto</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
                    <form method="post" action="/RicPlast3.1/Controller/edit_product.php?id=<?php echo (int)$product['id'] ?>">
                        <!-- Título -->
                        <div class="form-group">
                            <label for="product-title">Título del Producto</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-th-large"></i>
                                </span>
                                <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']); ?>">
                            </div>
                        </div>
                        <!-- Categoría -->
                        <div class="form-group">
                            <label for="product-categorie">Categoría</label>
                            <select class="form-control" name="product-categorie">
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($all_categories as $cat): ?>
                                    <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']) echo "selected"; ?>>
                                        <?php echo remove_junk($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Marca -->
                        <div class="form-group">
                            <label for="product-brand">Marca</label>
                            <input type="text" class="form-control" name="product-brand" value="<?php echo remove_junk($product['brand']); ?>">
                        </div>
                        <!-- Imagen -->
                        <div class="form-group">
                            <label for="product-photo">Imagen del Producto</label>
                            <select class="form-control" name="product-photo" id="product-photo-select">
                                <option value="" data-img-src="../uploads/products/no_image.jpg">Sin imagen</option>
                                <?php foreach ($all_photo as $photo): ?>
                                    <option value="<?php echo (int)$photo['id']; ?>" data-img-src="../uploads/products/<?php echo $photo['file_name']; ?>"
                                        <?php if ($product['media_id'] === $photo['id']) echo "selected"; ?>>
                                        <?php echo $photo['file_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Cantidad -->
                        <div class="form-group">
                            <label for="qty">Cantidad</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-shopping-cart"></i>
                                </span>
                                <input type="text" class="form-control" id="qty" name="product-quantity"
                                value="<?php echo $product['quantity'] == 0 ? 'No disponible' : remove_junk($product['quantity']); ?>">
                            </div>
                        </div>
                        <!-- Precios -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="buying-price">Precio de compra</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">S/.</span>
                                        <input type="number" class="form-control" name="buying-price" id="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="saleing-price">Precio de venta</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">S/.</span>
                                        <input type="number" class="form-control" name="saleing-price" id="saleing-price" value="<?php echo remove_junk($product['sale_price']); ?>" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Unidad de Venta -->
                        <div class="form-group">
                            <label for="product-unit">Unidad de Venta</label>
                            <select class="form-control" name="product-unit">
                                <option value="paquetes" <?php if ($product['unidad_venta'] === 'paquetes') echo 'selected'; ?>>Paquetes</option>
                                <option value="unidades" <?php if ($product['unidad_venta'] === 'unidades') echo 'selected'; ?>>Unidades</option>
                                <option value="cajas" <?php if ($product['unidad_venta'] === 'cajas') echo 'selected'; ?>>Cajas</option>
                                <option value="docenas" <?php if ($product['unidad_venta'] === 'docenas') echo 'selected'; ?>>Docenas</option>
                            </select>
                        </div>
                        <!-- Proveedor -->
                        <div class="form-group">
                            <label for="product-supplier">Proveedor</label>
                            <select class="form-control" name="product-supplier" required>
                                <option value="">Seleccione un proveedor</option>
                                <?php foreach ($all_suppliers as $supplier): ?>
                                    <option value="<?php echo (int)$supplier['id']; ?>" <?php if ($product['supplier_id'] === $supplier['id']) echo "selected"; ?>>
                                        <?php echo $supplier['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Botones de acción -->
                        <div class="form-group">
                            <button type="submit" name="product" id="update-button" class="btn btn-primary" style="background-color: #00BFFF; border-color: #00BFFF;">Actualizar</button>
                            <a href="/RicPlast3.1/views/product.php" class="btn btn-danger" style="background-color: #FF4500; border-color: #FF4500;">Cancelar</a>
                        </div>
                    </form>
                </div>
                <!-- Vista previa de la imagen a la derecha -->
                <div class="col-md-4">
                    <div id="image-preview" style="border: 1px solid #ddd; padding: 5px; width: 100%; height: 250px;">
                        <img id="preview-img" src="../uploads/products/no_image.jpg" alt="Vista previa de imagen" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("product-photo-select").addEventListener("change", function() {
    const selectedOption = this.options[this.selectedIndex];
    const imgSrc = selectedOption.getAttribute("data-img-src");
    const previewImg = document.getElementById("preview-img");

    if (imgSrc) {
        previewImg.src = imgSrc;
        previewImg.style.display = "block";
    } else {
        previewImg.src = "../uploads/products/no_image.jpg";
        previewImg.style.display = "block";
    }
});

document.getElementById("saleing-price").addEventListener("input", validatePrices);
document.getElementById("buying-price").addEventListener("input", validatePrices);

function validatePrices() {
    const buyingPrice = parseFloat(document.getElementById("buying-price").value);
    const saleingPrice = parseFloat(document.getElementById("saleing-price").value);

    if (saleingPrice < buyingPrice) {
        alert("El precio de venta no puede ser menor que el precio de compra.");
        document.getElementById("saleing-price").value = buyingPrice;
    }
}

window.addEventListener("load", function() {
    const productPhotoSelect = document.getElementById("product-photo-select");
    const selectedOption = productPhotoSelect.options[productPhotoSelect.selectedIndex];
    const imgSrc = selectedOption.getAttribute("data-img-src");
    const previewImg = document.getElementById("preview-img");

    if (imgSrc) {
        previewImg.src = imgSrc;
        previewImg.style.display = "block";
    } else {
        previewImg.src = "../uploads/products/no_image.jpg";
        previewImg.style.display = "block";
    }
});

document.getElementById("qty").addEventListener("input", function() {
    if (this.value.toLowerCase() === "no disponible" || this.value.trim() === "" || isNaN(this.value)) {
        this.value = 0; // Si el valor es "No disponible" o no es numérico, lo fuerza a 0
    }
});

document.getElementById("qty").addEventListener("input", function() {
    const value = parseInt(this.value, 10);

    if (isNaN(value) || value < 1) {
        this.value = 1; // Fuerza el valor mínimo a 1 si es inválido o menor a 1
    }
});
</script>

<?php include_once('../layouts/footer.php'); ?>
