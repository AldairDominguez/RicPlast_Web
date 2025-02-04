<?php include_once('Model/load.php'); ?>
<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

  if(empty($errors)){

    $user = authenticate_v2($username, $password);

        if($user):
           //create session with id
           $session->login($user['id']);
           //Update Sign in time
           updateLastLogIn($user['id']);
           // redirect user to group home page by user level
           if($user['user_level'] === '1'):
             $session->msg("s", "Hola ".$user['username'].", Bienvenido a RicPlast");
             redirect('/RicPlast3.1/views/admin.php',false);
           elseif ($user['user_level'] === '2'):
              $session->msg("s", "Hola ".$user['username'].", Bienvenido a RicPlast");
             redirect('special.php',false);
           else:
              $session->msg("s", "Hola".$user['username'].", Bienvenido a RicPlast");
             redirect('/RicPlast3.1/views/home.php',false);
           endif;

        else:
          $session->msg("d", "Lo sentimos, nombre de usuario/contraseña incorrectos.");
          redirect('../index.php',false);
        endif;

  } else {

     $session->msg("d", $errors);
     redirect('/RicPlast3.1/views/login_v2.php',false);
  }

?>
