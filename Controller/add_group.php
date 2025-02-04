<?php
  $page_title = 'Add Group';
  require($_SERVER['DOCUMENT_ROOT'] . '/RicPlast3.1/Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
   page_require_level(1);
?>
<?php
  if(isset($_POST['add'])){

   $req_fields = array('group-name','group-level');
   validate_fields($req_fields);

   $group_level = $_POST['group-level'];

   // Restricción de nivel de grupo entre 1 y 3
   if ($group_level < 1 || $group_level > 3) {
       $session->msg('d', '<b>¡Lo siento!</b> El nivel de grupo debe estar entre 1 y 3.');
       redirect('/RicPlast3.1/Controller/add_group.php', false);
   }

   if(find_by_groupName($_POST['group-name']) === false ){
     $session->msg('d','<b>¡Lo siento!</b> ¡Nombre de Grupo ingresado ya en la base de datos!');
     redirect('/RicPlast3.1/Controller/add_group.php', false);
   }elseif(find_by_groupLevel($group_level) === false) {
     $session->msg('d','<b>¡Lo siento!</b> ¡Ya ingresó al nivel de Grupo en la base de datos!');
     redirect('/RicPlast3.1/Controller/add_group.php', false);
   }
   
   if(empty($errors)){
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($group_level));
        $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO user_groups (";
        $query .="group_name,group_level,group_status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$level}','{$status}'";
        $query .=")";
        
        if($db->query($query)){
          //success
          $session->msg('s',"¡Se ha creado el grupo! ");
          redirect('/RicPlast3.1/Controller/add_group.php', false);
        } else {
          //failed
          $session->msg('d',' ¡Lo siento, no se pudo crear el grupo!');
          redirect('/RicPlast3.1/Controller/add_group.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('/RicPlast3.1/Controller/add_group.php',false);
   }
 }
?>
<?php include_once('../layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Agregar nuevo grupo de Roles</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="/RicPlast3.1/Controller/add_group.php" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Nombre de Rol</label>
              <input type="name" class="form-control" id="name" name="group-name">
        </div>
        <div class="form-group">
              <label for="level" class="control-label">Nivel de grupo</label>
              <input type="number" class="form-control" id="level" name="group-level" min="1" max="3">
        </div>
        <div class="form-group">
          <label for="status">Estado</label>
            <select id="status" class="form-control" name="status">
              <option value="1">Activo</option>
              <option value="0">Desactivado</option>
            </select>
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="add" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('../layouts/footer.php'); ?>
