<?php
  $page_title = 'Edit User';
  require_once('../Model/load.php');
  // Checkin ¿En qué nivel el usuario tiene permiso para ver esta página?
  page_require_level(1);
?>
<?php
  $e_user = find_by_id('users', (int)$_GET['id']);
  $groups = find_all('user_groups');
  if (!$e_user) {
    $session->msg("d", "Falta id. de usuario.");
    redirect('../users.php');
  }
?>

<?php
// Update User basic info
if (isset($_POST['update'])) {
    $req_fields = array('name', 'last-name', 'gender', 'username', 'level', 'status');
    validate_fields($req_fields);

    $errors = [];

    // Validar nombre (solo letras y espacios, max 50 caracteres)
    if (!preg_match("/^[A-Za-z\s]+$/", $_POST['name']) || strlen($_POST['name']) > 50) {
        $errors[] = "El nombre solo puede contener letras y espacios, y debe tener un máximo de 50 caracteres.";
    }

    // Validar apellidos (solo letras y espacios, max 60 caracteres)
    if (!preg_match("/^[A-Za-z\s]+$/", $_POST['last-name']) || strlen($_POST['last-name']) > 60) {
        $errors[] = "Los apellidos solo pueden contener letras y espacios, y deben tener un máximo de 60 caracteres.";
    }

    // Validar género (debe ser 'M', 'F' o 'O')
    if (empty($_POST['gender']) || !in_array($_POST['gender'], ['M', 'F', 'O'])) {
        $errors[] = "Debe seleccionar un género válido.";
    }

    // Validar nombre de usuario (max 10 caracteres)
    if (strlen($_POST['username']) > 10) {
        $errors[] = "El nombre de usuario no puede exceder los 10 caracteres.";
    }

    if (empty($errors)) {
        // Si no hay errores, proceder con la actualización
        $id       = (int)$e_user['id'];
        $name     = remove_junk($db->escape($_POST['name']));
        $last_name = remove_junk($db->escape($_POST['last-name']));
        $gender   = remove_junk($db->escape($_POST['gender']));
        $username = remove_junk($db->escape($_POST['username']));
        $level    = (int)$db->escape($_POST['level']);
        $status   = remove_junk($db->escape($_POST['status']));
        
        $sql = "UPDATE users SET name ='{$name}', last_name='{$last_name}', gender='{$gender}', username ='{$username}', user_level='{$level}', status='{$status}' WHERE id='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Cuenta actualizada");
            redirect('/RicPlast3.1/Controller/edit_user.php?id=' . (int)$e_user['id'], false);
        } else {
            $session->msg('d', '¡Lo siento, no se pudo actualizar!');
            redirect('/RicPlast3.1/Controller/edit_user.php?id=' . (int)$e_user['id'], false);
        }
    } else {
        $session->msg("d", implode(", ", $errors));
        redirect('/RicPlast3.1/Controller/edit_user.php?id=' . (int)$e_user['id'], false);
    }
}
?>

<?php
// Update user password
if (isset($_POST['update-pass'])) {
    $req_fields = array('password');
    validate_fields($req_fields);

    $errors = [];

    // Validar contraseña (6-20 caracteres, debe incluir letras y números)
    if (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z]).{6,20}$/", $_POST['password'])) {
        $errors[] = "La contraseña debe tener entre 6 y 20 caracteres y contener al menos una letra y un número.";
    }

    if (empty($errors)) {
        $id       = (int)$e_user['id'];
        $password = remove_junk($db->escape($_POST['password']));
        $h_pass   = sha1($password);

        $sql = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La contraseña del usuario ha sido actualizada.");
            redirect('/RicPlast3.1/Controller/edit_user.php?id=' . (int)$e_user['id'], false);
        } else {
            $session->msg('d', '¡Lo sentimos, no se pudo actualizar la contraseña de usuario!');
            redirect('/RicPlast3.1/Controller/edit_user.php?id=' . (int)$e_user['id'], false);
        }
    } else {
        $session->msg("d", implode(", ", $errors));
        redirect('/RicPlast3.1/Controller/edit_user.php?id=' . (int)$e_user['id'], false);
    }
}
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
  <div class="col-md-6">
     <div class="panel panel-default">
       <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Actualizar cuenta de <?php echo remove_junk(ucwords($e_user['name'])); ?>
        </strong>
       </div>
       <div class="panel-body">
          <form method="post" action="/RicPlast3.1/Controller/edit_user.php?id=<?php echo (int)$e_user['id'];?>" class="clearfix">
            <div class="form-group">
                <label for="name" class="control-label">Nombre</label>
                <input type="text" class="form-control" name="name" value="<?php echo remove_junk(ucwords($e_user['name'])); ?>" maxlength="50" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" required>
            </div>
            <div class="form-group">
                <label for="last-name" class="control-label">Apellidos</label>
                <input type="text" class="form-control" name="last-name" value="<?php echo remove_junk(ucwords($e_user['last_name'])); ?>" maxlength="60" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" required>
            </div>
            <div class="form-group">
                <label for="gender" class="control-label">Género</label>
                <select class="form-control" name="gender" required>
                  <option value="M" <?php if ($e_user['gender'] === 'M') echo 'selected="selected"'; ?>>Masculino</option>
                  <option value="F" <?php if ($e_user['gender'] === 'F') echo 'selected="selected"'; ?>>Femenino</option>
                  <option value="O" <?php if ($e_user['gender'] === 'O') echo 'selected="selected"'; ?>>Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username" class="control-label">Nombre de usuario</label>
                <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>" maxlength="10" required>
            </div>
            <div class="form-group">
              <label for="level">Rol del usuario</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group): ?>
                   <option <?php if ($group['group_level'] === $e_user['user_level']) echo 'selected="selected"'; ?> value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
              <label for="status">Estado</label>
                <select class="form-control" name="status">
                  <option <?php if ($e_user['status'] === '1') echo 'selected="selected"'; ?> value="1">Activo</option>
                  <option <?php if ($e_user['status'] === '0') echo 'selected="selected"'; ?> value="0">Desactivado</option>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="update" class="btn btn-info">Actualizar</button>
              <a href="/RicPlast3.1/views/users.php" class="btn btn-secondary">Regresar</a>
            </div>
        </form>
       </div>
     </div>
  </div>
  <!-- Change password form -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Cambiar contraseña de <?php echo remove_junk(ucwords($e_user['name'])); ?>
        </strong>
      </div>
      <div class="panel-body">
        <form action="/RicPlast3.1/Controller/edit_user.php?id=<?php echo (int)$e_user['id'];?>" method="post" class="clearfix">
          <div class="form-group">
                <label for="password" class="control-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Nueva contraseña" minlength="6" maxlength="20" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,20}" title="Debe contener entre 6 y 20 caracteres, incluyendo letras y números" required>
          </div>
          <div class="form-group clearfix">
              <button type="submit" name="update-pass" class="btn btn-danger pull-right">Cambiar</button>
              <a href="/RicPlast3.1/views/users.php" class="btn btn-secondary pull-left">Regresar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
