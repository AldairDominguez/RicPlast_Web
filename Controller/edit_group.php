<?php
  $page_title = 'Edit Group';
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
  page_require_level(1);
?>
<?php
  $e_group = find_by_id('user_groups', (int)$_GET['id']);
  if (!$e_group) {
    $session->msg("d", "Falta id. del grupo.");
    redirect('../group.php');
  }
?>
<?php
  if (isset($_POST['update'])) {
    $req_fields = array('group-name', 'group-level');
    validate_fields($req_fields);

    $errors = [];

    // Validar nombre del rol (solo letras y espacios, max 20 caracteres)
    if (!preg_match("/^[A-Za-z\s]+$/", $_POST['group-name']) || strlen($_POST['group-name']) > 20) {
        $errors[] = "El nombre del rol solo puede contener letras y espacios, y debe tener un máximo de 20 caracteres.";
    }

    if (empty($errors)) {
        $name   = remove_junk($db->escape($_POST['group-name']));
        $level  = remove_junk($db->escape($_POST['group-level']));
        $status = remove_junk($db->escape($_POST['status']));

        $query  = "UPDATE user_groups SET ";
        $query .= "group_name='{$name}', group_level='{$level}', group_status='{$status}' ";
        $query .= "WHERE id='{$db->escape($e_group['id'])}'";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
          $session->msg('s', "¡El Rol ha sido actualizado!");
          redirect('/RicPlast3.1/Controller/edit_group.php?id=' . (int)$e_group['id'], false);
        } else {
          $session->msg('d', '¡Lo siento, no se pudo actualizar el Rol!');
          redirect('/RicPlast3.1/Controller/edit_group.php?id=' . (int)$e_group['id'], false);
        }
    } else {
      $session->msg("d", implode(", ", $errors));
      redirect('/RicPlast3.1/Controller/edit_group.php?id=' . (int)$e_group['id'], false);
    }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Editar Usuario</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="/RicPlast3.1/Controller/edit_group.php?id=<?php echo (int)$e_group['id'];?>" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Nombre del Rol</label>
              <input type="text" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>" maxlength="20" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios, con un máximo de 20 caracteres" required>
        </div>
        <div class="form-group">
              <label for="level" class="control-label">Nivel de grupo</label>
              <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
        </div>
        <div class="form-group">
          <label for="status">Status</label>
              <select class="form-control" name="status">
                <option <?php if($e_group['group_status'] === '1') echo 'selected="selected"'; ?> value="1"> Activo </option>
                <option <?php if($e_group['group_status'] === '0') echo 'selected="selected"'; ?> value="0">Desactivado</option>
              </select>
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('../layouts/footer.php'); ?>
