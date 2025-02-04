<?php
  ob_start();
  require_once('../Model/load.php');
  if($session->isUserLoggedIn(true)) { redirect('/RicPlast3.1/views/home.php', false);}
?>

<div class="login-page">
    <div class="text-center">
       <h1>Bienvenido</h1>
       <p>Inicia sesión </p>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="../auth_v2.php" class="clearfix">
        <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input type="name" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="Password" class="control-label">Password</label>
            <input type="password" name= "password" class="form-control" placeholder="password">
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-info  pull-right">Login</button>
        </div>
    </form>
</div>
<?php include_once('../layouts/header.php'); ?>
