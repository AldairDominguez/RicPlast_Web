<?php
  $page_title = 'Edit categorie';
  require_once('../Model/load.php');
  // Checkin ¿Qué nivel de usuario tiene permiso para ver esta página?
  page_require_level(1);
?>
<?php
  //Mostrar todas las categorías.
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Categoría faltante id.");
    redirect('../views/categorie.php');
  }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('categorie-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  if(empty($errors)){
        $sql = "UPDATE categories SET name='{$cat_name}'";
       $sql .= " WHERE id='{$categorie['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Categoría actualizada con éxito");
       redirect('../views/categorie.php',false);
     } else {
       $session->msg("d", "¡Lo siento! Error al actualizar");
       redirect('../views/categorie.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('../views/categorie.php',false);
  }
}
?>
<?php include_once('../layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Edición <?php echo remove_junk(ucfirst($categorie['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="/RicPlast3.1/Controller/edit_categorie.php?id=<?php echo (int)$categorie['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Actualizar categoría</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('../layouts/footer.php'); ?>
