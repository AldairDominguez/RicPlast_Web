<?php
  $page_title = 'Home Page';
  require_once('../Model/load.php');
 //require_once(__DIR__ . '/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('../index.php', false);}
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center"style="background-color:#999999">
         <h1 style="color:white">¡BIENVENIDO A RICPLAST!</h1>
         <p>Simplemente navegue y descubra a qué página puede acceder.</p>
      </div>
    </div>
    <div style="max-width: 800px; margin:0 auto"> 
      <img src="../imagenes/us.svg" alt="" style="box-sizing: inherit; width: 100%;">
    </div>
 </div>
</div>
<?php include_once('../layouts/footer.php'); ?>
