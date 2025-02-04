<?php
   require($_SERVER['DOCUMENT_ROOT'] . '/RicPlast3.1/Model/load.php');
   //require_once(__DIR__ . '/load.php');
/*--------------------------------------------------------------*/
/*  Función para buscar todas las filas de la tabla de la base de datos por nombre de tabla
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Función para realizar consultas
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Función para buscar datos de la tabla por id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Función para eliminar datos de la tabla por ID
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Función para contar ID por nombre de tabla
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determinar si existe la tabla de base de datos
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user() {
    global $db;
    $results = array();
    $sql = "SELECT u.id, u.name, u.last_name, u.gender, u.username, u.user_level, u.status, u.last_login, ";
    $sql .= "g.group_name ";
    $sql .= "FROM users u ";
    $sql .= "LEFT JOIN user_groups g ";
    $sql .= "ON g.group_level = u.user_level ";
    $sql .= "ORDER BY u.name ASC";
    $result = find_by_sql($sql);
    return $result;
}

  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /*  Buscar todo el nombre del grupo
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Buscar nivel de grupo
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Función para comprobar qué nivel de usuario tiene acceso a la página.
  /*--------------------------------------------------------------*/
  function page_require_level($require_level){
    global $session;
    $current_user = current_user();
    $login_level = find_by_groupLevel($current_user['user_level']);
  
    if ($login_level === false) {
      // No se pudo encontrar el nivel de grupo correspondiente
      // Manejar el error aquí
    } elseif ($session->isUserLoggedIn(true)) {
      // El usuario está conectado
      if ($login_level['group_status'] === '0') {
        // El grupo está desactivado
        $session->msg('d','¡Este usuario de nivel ha sido baneando!');
        redirect('/RicPlast3.1/views/home.php',false);
      } elseif ($current_user['user_level'] <= (int)$require_level) {
        // El nivel de usuario es suficiente
        return true;
      } else {
        // El nivel de usuario no es suficiente
        $session->msg("d", "¡Lo siento! no tienes permiso para ver la pagina.");
        redirect('/RicPlast3.1/views/home.php', false);
      }
    } else {
      // El usuario no está conectado
      $session->msg('d','Por favor Iniciar sesión...');
      redirect('index.php', false);
    }
  }
   /*--------------------------------------------------------------*/
   /* Función para encontrar todos los nombres de productos
   /* UNE con la tabla de base de datos de medios y categorías
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.name,p.brand,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS categorie,m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /*Función para encontrar todos los nombres de productos
  /* Solicitud proveniente de ajax.php para sugerencia automática
  /*--------------------------------------------------------------*/

  function find_product_by_title($product_name) {
    global $db;
    $p_name = remove_junk($db->escape($product_name));
    $sql = "SELECT 
                p.id, 
                p.name, 
                p.sale_price, 
                p.brand, 
                c.name AS category_name 
            FROM products p 
            LEFT JOIN categories c ON p.categorie_id = c.id 
            WHERE p.name LIKE '%$p_name%' 
            LIMIT 5";
    $result = find_by_sql($sql);
    return $result;
}
  /*--------------------------------------------------------------*/
  /* Función para encontrar toda la información del producto por título del producto
  /* Solicitud proveniente de ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    $result = find_by_sql($sql);
  
    if ($result === false) {
      // La consulta no se pudo ejecutar correctamente
      // Manejar el error aquí
    } else {
      return $result;
    }
  }

  /*--------------------------------------------------------------*/
  /* Función para actualizar la cantidad de producto
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Función para mostrar Producto reciente agregado
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Función para encontrar el producto más vendido
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /*  Función para encontrar todas las ventas.
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /*  Función para mostrar venta reciente
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/*  Función para Generar informe de ventas por dos fechas.
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date, $end_date) {
  global $db;
  $start_date = date("Y-m-d", strtotime($start_date));
  $end_date = date("Y-m-d", strtotime($end_date));

  $sql  = "SELECT s.date, p.name, p.sale_price, p.buy_price, ";
  $sql .= "COUNT(s.product_id) AS total_records, ";
  $sql .= "SUM(s.qty) AS total_sales, ";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price, ";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}' ";
  $sql .= "GROUP BY s.date, p.name, p.sale_price, p.buy_price ";
  $sql .= "ORDER BY s.date DESC";

  return $db->query($sql);
}

function find_product_by_dates($start_date, $end_date) {
  global $db;
  $sql = "SELECT p.date, p.name, c.name as 'categoria', p.quantity, p.buy_price, p.sale_price
  FROM products p
  INNER JOIN categories c ON c.id = p.categorie_id
  WHERE p.date BETWEEN '{$start_date}' AND '{$end_date}'
  ";
  return $db->query($sql);
}

/*--------------------------------------------------------------*/
/*  Función para generar informe de ventas diario
/*--------------------------------------------------------------*/
function dailySales($year, $month) {
  global $db;
  $sql  = "SELECT DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name, ";
  $sql .= "SUM(s.qty) AS total_quantity, ";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "WHERE DATE_FORMAT(s.date, '%Y-%m') = '{$year}-{$month}' ";
  $sql .= "GROUP BY DATE_FORMAT(s.date, '%Y-%m-%e'), p.name ";
  $sql .= "ORDER BY DATE_FORMAT(s.date, '%Y-%m-%e') ASC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/*  Función para generar informe de ventas mensual
/*--------------------------------------------------------------*/
function monthlySales($year) {
  global $db;
  $sql  = "SELECT DATE_FORMAT(s.date, '%Y-%m-%e') AS date, p.name, ";
  $sql .= "SUM(s.qty) AS total_quantity, ";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
  $sql .= "WHERE DATE_FORMAT(s.date, '%Y') = '{$year}' ";
  $sql .= "GROUP BY DATE_FORMAT(s.date, '%Y-%m-%e'), p.name ";
  $sql .= "ORDER BY DATE_FORMAT(s.date, '%Y-%m-%e') ASC";
  return find_by_sql($sql);
}


?>
