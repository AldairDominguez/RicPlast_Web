<?php
  $page_title = 'Add User';
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
  page_require_level(1);
  $groups = find_all('user_groups');
?>

<?php
  if(isset($_POST['add_user'])){

    $req_fields = array('full-name', 'last-name', 'username', 'password', 'level', 'gender');
    validate_fields($req_fields);

    // Validaciones adicionales
    $errors = [];

    // Validar nombre completo (solo letras y espacios, max 50 caracteres)
    if (!preg_match("/^[A-Za-z\s]+$/", $_POST['full-name']) || strlen($_POST['full-name']) > 50) {
        $errors[] = "El nombre completo solo puede contener letras y espacios, y debe tener un máximo de 50 caracteres.";
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

    // Validar contraseña (6-20 caracteres, debe incluir letras y números)
    if (!preg_match("/^(?=.*\d)(?=.*[a-zA-Z]).{6,20}$/", $_POST['password'])) {
        $errors[] = "La contraseña debe tener entre 6 y 20 caracteres y contener al menos una letra y un número.";
    }

    if(empty($errors)){
        // Si no hay errores, proceder con la inserción en la base de datos
        $name       = remove_junk($db->escape($_POST['full-name']));
        $last_name  = remove_junk($db->escape($_POST['last-name']));
        $gender     = remove_junk($db->escape($_POST['gender']));
        $username   = remove_junk($db->escape($_POST['username']));
        $password   = remove_junk($db->escape($_POST['password']));
        $user_level = (int)$db->escape($_POST['level']);
        $password   = sha1($password);

        $query = "INSERT INTO users (";
        $query .= "name, last_name, gender, username, password, user_level, status";
        $query .= ") VALUES (";
        $query .= " '{$name}', '{$last_name}', '{$gender}', '{$username}', '{$password}', '{$user_level}', '1'";
        $query .= ")";

        if($db->query($query)){
          // Success
          $session->msg('s',"¡La cuenta de usuario ha sido creada!");
          redirect('/RicPlast3.1/Controller/add_user.php', false);
        } else {
          // Failed
          $session->msg('d',' ¡Lo siento, no se pudo crear la cuenta!');
          redirect('/RicPlast3.1/Controller/add_user.php', false);
        }
    } else {
        // Mostrar errores
        $session->msg("d", implode(", ", $errors));
        redirect('/RicPlast3.1/Controller/add_user.php',false);
    }
  }
?>

<?php include_once('../layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Agregar nuevo usuario</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="/RicPlast3.1/Controller/add_user.php" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" name="full-name" id="full-name" placeholder="Nombre completo" maxlength="50" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" required>
            </div>
            <div class="form-group">
                <label for="last-name">Apellidos</label>
                <input type="text" class="form-control" name="last-name" id="last-name" placeholder="Apellidos" maxlength="60" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" required>
            </div>
            <div class="form-group">
                <label for="gender">Género</label>
                <select class="form-control" name="gender" id="gender" required>
                    <option value="">Seleccione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="O">Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Nombre de usuario" maxlength="10" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" minlength="6" maxlength="20" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,20}" title="Debe contener entre 6 y 20 caracteres, incluyendo letras y números" required>
            </div>
            <div class="form-group">
              <label for="level">Rol del usuario</label>
                <select class="form-control" name="level" required>
                  <?php foreach ($groups as $group ):?>
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary">Agregar usuario</button>
              <a href="/RicPlast3.1/views/users.php" class="btn btn-secondary">Regresar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script>
function validateForm() {
    // Validar nombre completo (solo letras y espacios)
    var name = document.getElementById('full-name').value;
    if (!/^[A-Za-z\s]+$/.test(name)) {
        alert("El nombre solo puede contener letras y espacios.");
        return false;
    }

    // Validar apellidos (solo letras y espacios)
    var lastName = document.getElementById('last-name').value;
    if (!/^[A-Za-z\s]+$/.test(lastName)) {
        alert("Los apellidos solo pueden contener letras y espacios.");
        return false;
    }

    // Validar género
    var gender = document.getElementById('gender').value;
    if (gender === "") {
        alert("Debe seleccionar un género.");
        return false;
    }

    // Validar nombre de usuario (max 10 caracteres)
    var username = document.getElementById('username').value;
    if (username.length > 10) {
        alert("El nombre de usuario no puede tener más de 10 caracteres.");
        return false;
    }

    // Validar contraseña (6-20 caracteres, letras y números)
    var password = document.getElementById('password').value;
    if (!/^(?=.*\d)(?=.*[a-zA-Z]).{6,20}$/.test(password)) {
        alert("La contraseña debe tener entre 6 y 20 caracteres y contener al menos una letra y un número.");
        return false;
    }

    return true;
}
</script>

<?php include_once('../layouts/footer.php'); ?>
