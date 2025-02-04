<?php
  $page_title = 'All User';
  require_once('../Model/load.php');
?>
<?php
// Checkin ¿En qué nivel el usuario tiene permiso para ver esta página?
 page_require_level(1);
// Extraer toda la base de datos de formularios de usuario
 $all_users = find_all_user();
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Usuarios</span>
       </strong>
         <a href="/RicPlast3.1/Controller/add_user.php" class="btn btn-info pull-right">Agregar nuevo usuario</a>
      </div>
     <div class="panel-body" style="background-color:#D9D1D0;">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Género</th>
            <th>Username</th>
            <th class="text-center" style="width: 15%;">Rol del usuario</th>
            <th class="text-center" style="width: 10%;">Estado</th>
            <th style="width: 20%;">Último acceso</th>
            <th class="text-center" style="width: 100px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_user['name']))?></td>
           <td><?php echo remove_junk(ucwords($a_user['last_name']))?></td>
           <td>
             <?php 
             if($a_user['gender'] === 'M') echo "Masculino"; 
             elseif($a_user['gender'] === 'F') echo "Femenino"; 
             else echo "Otro"; 
             ?>
           </td>
           <td><?php echo remove_junk(ucwords($a_user['username']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
           <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Activo"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Desactivado"; ?></span>
          <?php endif;?>
           </td>
           <td><?php echo read_date($a_user['last_login'])?></td>
           <td class="text-center">
             <div class="btn-group">
                <a href="/RicPlast3.1/Controller/edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <?php if((int)$a_user['id'] > 3): ?>
                <a href="/RicPlast3.1/Controller/delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
                <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
                </a>
                <?php else: ?>
                <button class="btn btn-xs btn-danger" title="No se puede eliminar" disabled>
                <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
                </button>
                <?php endif; ?>
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
