<?php include_once('load.php'); ?>
<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);



if(empty($errors)){
  $user_id = authenticate($username, $password);
  if($user_id){
    //crear sesión con id
     $session->login($user_id);
    //Actualizar hora de inicio de sesión
     updateLastLogIn($user_id);
     $session->msg("s", "Bienvenido a RicPlast");
     redirect('/RicPlast3.1/views/home.php',false);

  } else {
    $session->msg("d", "Lo sentimos, nombre de usuario/contraseña incorrectos.");
    redirect('../index.php',false);
  }

} else {
   $session->msg("d", $errors);
   redirect('../index.php',false);
}

?>
