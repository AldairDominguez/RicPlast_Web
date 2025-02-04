<?php
  $page_title = 'All Group';
  require_once('../Model/load.php');
  
  page_require_level(1);
  $all_groups = find_all('user_groups');
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); // Muestra el mensaje de la sesión ?>
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
      <a href="/RicPlast3.1/Controller/add_group.php" class="btn btn-info pull-right btn-sm"> Agregar nuevo Rol</a>
    </div>
    <div class="panel-body" style="background-color:#D9D1D0;">
      <table class="table table-bordered">
        <thead>
          <tr style="color:#000">
            <th class="text-center" style="width: 50px;">#</th>
            <th>Nombre del Rol</th>
            <th class="text-center" style="width: 20%;">Nivel de grupo</th>
            <th class="text-center" style="width: 15%;">Estados</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_groups as $a_group): ?>
          <tr style="color:#000">
            <td class="text-center"><?php echo count_id();?></td>
            <td><?php echo remove_junk(ucwords($a_group['group_name']))?></td>
            <td class="text-center">
              <?php echo remove_junk(ucwords($a_group['group_level']))?>
            </td>
            <td class="text-center">
              <?php if($a_group['group_status'] === '1'): ?>
                <span class="label label-success"><?php echo "Active"; ?></span>
              <?php else: ?>
                <span class="label label-danger"><?php echo "Deactive"; ?></span>
              <?php endif;?>
            </td>
            <td class="text-center">
              <div class="btn-group">
                <a href="/RicPlast3.1/Controller/edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <?php if((int)$a_group['id'] > 3): ?>
                <a href="/RicPlast3.1/Controller/delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
