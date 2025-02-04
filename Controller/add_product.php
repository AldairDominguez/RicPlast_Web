<?php
$page_title = 'Add Product';
require_once('../Model/load.php');
// Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_suppliers = find_by_sql("SELECT id, name FROM suppliers WHERE status = 'Activo'");// Obtener todos los proveedores
?>
<?php
if (isset($_POST['add_product'])) {
  $req_fields = array('product-title', 'product-brand', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price', 'product-supplier');
  validate_fields($req_fields);

  $p_name  = remove_junk($db->escape($_POST['product-title']));
  $p_brand = remove_junk($db->escape($_POST['product-brand']));
  $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
  $p_qty   = $_POST['product-quantity'];
  $p_buy   = $_POST['buying-price'];
  $p_sale  = $_POST['saleing-price'];
  $p_unit  = remove_junk($db->escape($_POST['product-unit']));
  $p_supplier = remove_junk($db->escape($_POST['product-supplier'])); // Captura el proveedor seleccionado

  // Verificar que cantidad, precio de compra y precio de venta no sean negativos
  if ($p_qty < 0 || $p_buy < 0 || $p_sale < 0) {
      $session->msg('d', '<b>¡Lo siento!</b> La cantidad, el precio de compra y el precio de venta deben ser valores positivos.');
      redirect('/RicPlast3.1/Controller/add_product.php', false);
  }

  // Verificar si el producto ya existe con el mismo nombre, categoría y marca
  $existing_product = find_by_sql("SELECT * FROM products WHERE name='{$p_name}' AND categorie_id='{$p_cat}' AND brand='{$p_brand}' LIMIT 1");
  if (!empty($existing_product)) {
      $session->msg('d', '<b>¡Lo siento!</b> Ya existe un producto con el mismo nombre, marca y categoría.');
      redirect('/RicPlast3.1/Controller/add_product.php', false);
  }

  if (empty($errors)) {
      $p_qty   = remove_junk($db->escape($p_qty));
      $p_buy   = remove_junk($db->escape($p_buy));
      $p_sale  = remove_junk($db->escape($p_sale));
      if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
          $media_id = '0';
      } else {
          $media_id = remove_junk($db->escape($_POST['product-photo']));
      }
      $date    = make_date();
      $query  = "INSERT INTO products (";
      $query .= " name, brand, quantity, buy_price, sale_price, categorie_id, media_id, supplier_id, date, unidad_venta";
      $query .= ") VALUES (";
      $query .= " '{$p_name}', '{$p_brand}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$p_supplier}', '{$date}', '{$p_unit}'";
      $query .= ")";
      $query .= " ON DUPLICATE KEY UPDATE name='{$p_name}'";
      if ($db->query($query)) {
          $session->msg('s', "Producto agregado");
          redirect('/RicPlast3.1/Controller/add_product.php', false);
      } else {
          $session->msg('d', ' ¡Lo siento, no se pudo agregar!');
          redirect('product.php', false);
      }
  } else {
      $session->msg("d", $errors);
      redirect('/RicPlast3.1/Controller/add_product.php', false);
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
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Añadir nuevo producto</span>
        </strong>
      </div>
      <div class="panel-body" style="background-color:#D9D1D0;">
        <div class="col-md-12">
          <form method="post" action="/RicPlast3.1/Controller/add_product.php" class="clearfix">
            <h4>Nombre y Marca del Producto</h4>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Nombre del producto" required>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-pencil"></i>
                </span>
                <input type="text" class="form-control" name="product-brand" placeholder="Marca del producto" required>
              </div>
            </div>
            <h4>Información del Producto</h4>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <label for="product-categorie">Categoría</label>
                  <select class="form-control" name="product-categorie">
                    <option value="">Seleccionar categoría de producto</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="product-photo">Imagen</label>
                  <select class="form-control" name="product-photo" id="product-photo-select">
                    <option value="" data-img-src="../uploads/products/no_image.jpg">Sin imagen</option>
                    <?php foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>" data-img-src="../uploads/products/<?php echo $photo['file_name']; ?>">
                        <?php echo $photo['file_name'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <h4>Precios y Cantidad</h4>
            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <label for="product-quantity">Cantidad</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Cantidad de producto" step="0.01" min="0">
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="buying-price">Precio de Compra</label>
                  <div class="input-group">
                    <span class="input-group-addon">S/.</span>
                    <input type="number" class="form-control" name="buying-price" id="buying-price" placeholder="Precio de compra" step="0.01" min="0">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="saleing-price">Precio de Venta</label>
                  <div class="input-group">
                    <span class="input-group-addon">S/.</span>
                    <input type="number" class="form-control" name="saleing-price" id="saleing-price" placeholder="Precio de venta" step="0.01" min="0">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
            <h4>Proveedor</h4>
            <div class="form-group">
              <label for="product-supplier">Seleccione a su Proveedor</label>
              <select class="form-control" name="product-supplier" required>
                <option value="">Seleccionar proveedor</option>
                <?php foreach ($all_suppliers as $supplier): ?>
                  <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <h4>Presentación</h4>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-tags"></i>
                </span>
                <select class="form-control" name="product-unit">
                  <option value="paquetes">Paquetes</option>
                  <option value="unidades">Unidades</option>
                  <option value="cajas">Cajas</option>
                  <option value="docenas">Docenas</option>
                </select>
              </div>
            </div>
            <button type="submit" name="add_product" class="btn btn-danger">Agregar producto</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Vista previa de la imagen a la derecha -->
  <div class="col-md-4">
    <div id="image-preview" style="border: 1px solid #ddd; padding: 5px; width: 100%; height: 250px;">
      <img id="preview-img" src="../uploads/products/no_image.jpg" alt="Vista previa de imagen" style="width: 100%; height: 100%; object-fit: contain;">
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

  // Mostrar la imagen actual seleccionada al cargar la página
  window.addEventListener("load", function() {
    const selectedOption = document.getElementById("product-photo-select").options[document.getElementById("product-photo-select").selectedIndex];
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
</script>
<script>
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
</script>

<?php include_once('../layouts/footer.php'); ?>
