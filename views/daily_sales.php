<?php
  $page_title = 'Ventas Diarias';
  require_once('../Model/load.php');
  //  Checkin ¿En qué nivel el usuario tiene permiso para ver esta página?
   page_require_level(3);
?>

<?php
// Establecer el idioma en español
setlocale(LC_TIME, 'es_ES.utf8');

// Establecer la zona horaria de Lima, Perú
date_default_timezone_set('America/Lima');

// Obtener el año y el mes actual en la zona horaria de Lima
$year  = date('Y');
$month = date('m');

// Obtener las ventas diarias para el año y mes actual
$sales = dailySales($year,$month);

// Utilizar las ventas para generar el contenido HTML
echo "<ul>";
echo "</ul>";
?>
<?php include_once('../layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Ventas diarias</span>
          </strong>
        </div>
        <div class="panel-body" style="background-color:#D9D1D0;">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Nombre del producto </th>
                <th class="text-center" style="width: 15%;"> Cantidad vendida</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Fecha </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['name']); ?></td>
               <td class="text-center"><?php echo (int)$sale['total_quantity']; ?></td>
               <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
               <td class="text-center"><?php echo $sale['date']; ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('../layouts/footer.php'); ?>
