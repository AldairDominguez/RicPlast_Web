<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title><?php if (!empty($page_title))
            echo remove_junk($page_title);
          elseif (!empty($user))
            echo ucfirst($user['name']);
          else echo "Simple inventory System"; ?>
  </title>
  <link rel="stylesheet" href="/RicPlast3.1/libs/css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
  <link rel="stylesheet" href="/RicPlast3.1/libs/css/main.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://fontawesome.com/icons/house-night?f=classic&s=regular">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
  <?php if ($session->isUserLoggedIn(true)) : ?>
    <header id="header"style="background-color:#15315A">
      <div class="logo pull-left titulo-ricplast" style="background-color: #1446A0; width:249px"> RICPLAST - Inventario </div>
      <div class="header-content">
        <div class="header-date pull-left">
          <?php
          setlocale(LC_TIME, 'es_PE.UTF-8');
          date_default_timezone_set('America/Lima');
          $fecha = strftime('%d de %B de %Y, %I:%M %p');
          $fecha = str_replace(
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
            array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'),

            $fecha
          );
          echo "<strong>{$fecha}</strong>";
          echo "<strong style='padding-left:50px; text-transform:uppercase;  color:WHITE'>".$user['username']."</strong>";
          ?>
        </div>
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="../uploads/users/<?php echo $user['image']; ?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="/RicPlast3.1/views/profile.php?id=<?php echo (int)$user['id']; ?>">
                    <i class="glyphicon glyphicon-user"></i>
                    Perfil
                  </a>
                </li>
                <li>
                  <a href="/RicPlast3.1/Controller/edit_account.php" title="edit account">
                    <i class="glyphicon glyphicon-cog"></i>
                    Ajustes
                  </a>
                </li>
                <li class="last">
                  <a href="/RicPlast3.1/logout.php">
                    <i class="glyphicon glyphicon-off"></i>
                    Cerrar sesión
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <div class="sidebar">
      <?php if ($user['user_level'] === '1') : ?>
        <!-- admin menu -->
        <?php include_once('admin_menu.php'); ?>

      <?php elseif ($user['user_level'] === '2') : ?>
        <!-- Special user -->
        <?php include_once('special_menu.php'); ?>

      <?php elseif ($user['user_level'] === '3') : ?>
        <!-- Inventario menu -->
        <?php include_once('vendedor.php'); ?>

      <?php endif; ?>

    </div>
  <?php endif; ?>

  <div class="page">
    <div class="container-fluid">