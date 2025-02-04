<?php
$page_title = 'All Image';
require_once('../Model/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php $media_files = find_all('media'); ?>
<?php
if (isset($_POST['submit'])) {
  $photo = new Media();
  $photo->upload($_FILES['file_upload']);
  if ($photo->process_media()) {
    $session->msg('s', 'Se ha subido la foto.');
    redirect('/RicPlast3.1/views/media.php');
  } else {
    $session->msg('d', join($photo->errors));
    redirect('/RicPlast3.1/views/media.php');
  }
}

?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Todas las fotos</span>
        <div class="pull-right">
          <form class="form-inline" action="/RicPlast3.1/views/media.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-btn">
                  <!-- Botón personalizado para seleccionar foto -->
                  <label class="btn btn-primary btn-file" id="file-label" style="
                        background-color: #403B4A;
                        color: white; 
                        box-shadow: 0 0 20px #eee; 
                        border-radius: 10px; 
                        display: block; 
                        text-align: center; 
                        padding: 10px 20px; 
                        cursor: pointer;">
                    SELECCIONAR FOTO
                    <input type="file" name="file_upload" multiple="multiple" style="display: none;" onchange="changeButtonColor()" />
                  </label>
                </span>
                <button type="submit" name="submit" class="btn btn-default">Subir</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="panel-body" style="background-color:#D9D1D0;">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center">Foto</th>
              <th class="text-center">Nombre de la foto</th>
              <th class="text-center" style="width: 20%;">Tipo de foto</th>
              <th class="text-center" style="width: 50px;">Comportamiento</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($media_files as $media_file) : ?>
              <tr class="list-inline">
                <td class="text-center"><?php echo count_id(); ?></td>
                <td class="text-center">
                  <img src="../uploads/products/<?php echo $media_file['file_name']; ?>" class="img-thumbnail" />
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_name']; ?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type']; ?>
                </td>
                <td class="text-center">
                  <a href="/RicPlast3.1/Controller/delete_media.php?id=<?php echo (int) $media_file['id']; ?>" class="btn btn-danger btn-xs" title="Eliminar">
                  <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function changeButtonColor() {
    // Cambia el color del botón al verde (#41e45d) cuando se selecciona un archivo
    document.getElementById("file-label").style.backgroundColor = "#41e45d";
  }
</script>



<?php include_once('../layouts/footer.php'); ?>