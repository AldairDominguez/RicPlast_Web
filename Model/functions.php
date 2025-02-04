<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* 
Función para el primer carácter en mayúsculas
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Función para comprobar que los campos de entrada no estén vacíos
/*--------------------------------------------------------------*/
function validate_sales_fields($sales){
  global $errors;
  foreach ($sales as $index => $sale) {
    foreach (['product_id', 'quantity', 'price', 'total', 'date'] as $field) {
      if (!isset($sale[$field]) || $sale[$field] === '') {
        $errors[] = "El campo {$field} en el producto {$index} no puede estar en blanco.";
      }
    }
  }
}

function validate_fields($fields) {
  global $errors;
  foreach ($fields as $field) {
      $val = isset($_POST[$field]) ? trim($_POST[$field]) : '';
      if (empty($val)) {
          $errors[] = "El campo {$field} no puede estar en blanco.";
      }
  }
}

/*--------------------------------------------------------------*/
/* Función para mostrar el mensaje de sesión
   Ej. echo displayt_msg($mensaje);
/*--------------------------------------------------------------*/
function display_msg($msg = ''){
  $output = '';
  if (!empty($msg)) {
     foreach ($msg as $key => $value) {
        if (is_array($value)) {
           // Manejar valores no válidos
           $value = 'Mensaje inválido.';
        }
        $output .= "<div class=\"alert alert-{$key}\">";
        $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
        $output .= remove_junk(first_character($value));
        $output .= "</div>";
     }
     return $output;
  } else {
    return '';
  }
}
/*--------------------------------------------------------------*/
/* Función para redireccionar
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Función para conocer el precio total de venta, el precio de compra y las ganancias.
/*--------------------------------------------------------------*/
function total_price($totals){
   $sum = 0;
   $sub = 0;
   foreach($totals as $total ){
     $sum += $total['total_saleing_price'];
     $sub += $total['total_buying_price'];
     $profit = $sum - $sub;
   }
   return array($sum,$profit);
}
/*--------------------------------------------------------------*/
/* Función para fecha y hora legible
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('F j, Y, g:i:s a', strtotime($str));
     else
      return null;
  }
/*--------------------------------------------------------------*/
/* Función para hacer legible la fecha y hora
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/*  Función para crear cadenas aleatorias
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}



?>
