<?php
  $page_title = 'All categories';
  require_once('../Model/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_categories = find_all('categories');
?>
<?php
if (isset($_POST['add_cat'])) {
  $req_field = array('categorie-name');
  validate_fields($req_field);

  // Validar que el nombre de categoría solo contenga letras y tenga máximo 20 caracteres en el lado del servidor
  $cat_name = $_POST['categorie-name'];
  if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $cat_name)) {
      $session->msg("d", "El nombre de la categoría solo debe contener letras.");
      redirect('../views/categorie.php', false);
  }
  if (strlen($cat_name) > 20) {
      $session->msg("d", "El nombre de la categoría no puede exceder los 20 caracteres.");
      redirect('../views/categorie.php', false);
  }

  // Comprobar si la categoría ya existe en la base de datos
  $sql = "SELECT * FROM categories WHERE name = '{$cat_name}' LIMIT 1";
  $existingProduct = $db->query($sql);
  if ($existingProduct && $existingProduct->num_rows > 0) {
      $session->msg("d", "La categoría ya existe.");
      redirect('../views/categorie.php', false);
  } else {
      if (empty($errors)) {
          $sql  = "INSERT INTO categories (name)";
          $sql .= " VALUES ('{$cat_name}')";
          if ($db->query($sql)) {
              $session->msg("s", "Categoría agregada exitosamente");
              redirect('../views/categorie.php', false);
          } else {
              $session->msg("d", "Lo sentimos. No se pudo insertar.");
              redirect('../views/categorie.php', false);
          }
      } else {
          $session->msg("d", $errors);
          redirect('../views/categorie.php', false);
      }
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
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Agregar nueva categoría</span>
        </strong>
      </div>
      <div class="panel-body" style="background-color:#D9D1D0;">
        <form method="post" action="/RicPlast3.1/views/categorie.php">
          <div class="form-group">
              <input type="text" class="form-control" name="categorie-name" placeholder="Nombre de categoría" maxlength="20" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y un máximo de 20 caracteres" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')">
          </div>
          <button type="submit" name="add_cat" class="btn btn-primary">Añadir categoría</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Todas las categorias</span>
        </strong>
      </div>
      <div class="panel-body" style="background-color:#D9D1D0;">
        <table class="table table-bordered table-striped table-hover">
          <thead>
              <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Categorías</th>
                  <th class="text-center" style="width: 100px;">Actions</th>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($all_categories as $cat):?>
              <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="/RicPlast3.1/Controller/edit_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="/RicPlast3.1/Controller/delete_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                      <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
                      </a>
                    </div>
                  </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
