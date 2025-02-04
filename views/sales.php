<?php
  $page_title = 'All sale';
  require_once('../Model/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
$sales = find_all_sale();
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
            <span>Todas las ventas</span>
          </strong>

          <!-- Creación de botón con diseño style -->
          <div class="pull-right">
            <a href="/RicPlast3.1/Controller/add_sale.php" class="buttonV">
              <div data-tooltip="Hacer una venta" class="buttonV-wrapper">
                <div class="text">Hacer una venta</div>
                <span class="icon">
                  <svg viewBox="0 0 16 16" class="bi bi-cart2" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"></path>
                  </svg>
                </span>
              </div>
            </a>
          </div>
          <!------------------------------------------->
        </div>
        <div class="panel-body" style="background-color:#D9D1D0;">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th>Nombre del producto</th>
                <th class="text-center" style="width: 15%;">Cantidad</th>
                <th class="text-center" style="width: 15%;">Total</th>
                <th class="text-center" style="width: 15%;">Fecha</th>
                <th class="text-center" style="width: 100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="/RicPlast3.1/Controller/edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-warning btn-xs" title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php include_once('../layouts/footer.php'); ?>
