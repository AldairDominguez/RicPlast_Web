<?php
  $page_title = 'Change Password';
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
  page_require_level(3);
?>
<?php $user = current_user(); ?>
<?php
  if(isset($_POST['update'])){

    $req_fields = array('new-password','old-password','id' );
    validate_fields($req_fields);

    if(empty($errors)){

             if(sha1($_POST['old-password']) !== current_user()['password'] ){
               $session->msg('d', "Tu antigua contraseña no coincide");
               redirect('/RicPlast3.1/views/change_password.php',false);
             }

            $id = (int)$_POST['id'];
            $new = remove_junk($db->escape(sha1($_POST['new-password'])));
            $sql = "UPDATE users SET password ='{$new}' WHERE id='{$db->escape($id)}'";
            $result = $db->query($sql);
                if($result && $db->affected_rows() === 1):
                  $session->logout();
                  $session->msg('s',"Inicie sesión con su nueva contraseña.");
                  redirect('../index.php', false);
                else:
                  $session->msg('d',' ¡Lo siento, no se pudo actualizar!');
                  redirect('/RicPlast3.1/views/change_password.php', false);
                endif;
    } else {
      $session->msg("d", $errors);
      redirect('/RicPlast3.1/views/change_password.php',false);
    }
  }
?>
<?php include_once('../layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Change your password</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="/RicPlast3.1/views/change_password.php" class="clearfix">
        <div class="form-group">
              <label for="newPassword" class="control-label">Nueva contraseña</label>
              <input type="password" class="form-control" name="new-password" placeholder="New password">
        </div>
        <div class="form-group">
              <label for="oldPassword" class="control-label">Contraseña anterior</label>
              <input type="password" class="form-control" name="old-password" placeholder="Old password">
        </div>
        <div class="form-group clearfix">
               <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
                <button type="submit" name="update" class="btn btn-info">Cambiar</button>
        </div>
    </form>
</div>
<?php include_once('../layouts/footer.php'); ?>
