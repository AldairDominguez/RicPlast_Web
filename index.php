<?php
ob_start();
require_once('Model/load.php');
if ($session->isUserLoggedIn(true)) {
  redirect('/RicPlast3.1/views/home.php', false);
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="contenedor-login" style="display: grid; grid-template-columns: repeat(2,1fr); gap:10px">
  <div class="imagen" style="position: relative; height: 350px;">
    <img src="imagenes/login.svg" alt="login imagen" style="height: 100%;">
  </div>
  <div class="login-page">
    <div class="contenedor">
      <div class="text-center">
        <h1 class="login-titulo">Bienvenido</h1>
        <p>Inicia sesión </p>
      </div>
      <?php echo display_msg($msg); ?>
      <form method="post" action="/RicPlast3.1/Model/auth.php" class="clearfix" onsubmit="return validateForm()">
        <div class="form-group">
          <label for="username" class="control-label">Username</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Username" maxlength="10" pattern="[A-Za-z0-9]{1,10}" title="El nombre de usuario debe contener solo letras y números, máximo 10 caracteres" required>
        </div>
        <div class="form-group">
          <label for="Password" class="control-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" minlength="6" maxlength="20" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,20}" title="La contraseña debe tener entre 6 y 20 caracteres e incluir al menos una letra y un número" required>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-info pull-right login-buton">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
