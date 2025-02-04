<?php
  $page_title = 'All Product';
  require_once('../Model/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $products = join_product_table();
?>
<?php include_once('../layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <!-- creacion de botton con diseño style -->
         <div class="pull-right">
           <button class="buttonP" onclick="window.location.href='/RicPlast3.1/Controller/add_product.php'"></button>
         </div>
         <!------------------------------------------->
        </div>
        <div class="panel-body" style="background-color:#D9D1D0;">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Foto</th>
                <th> Titulo del producto </th>
                <th class="text-center" style="width: 10%;"> Marca </th>
                <th class="text-center" style="width: 10%;"> Categoría </th>
                <th class="text-center" style="width: 10%;"> En stock </th>
                <th class="text-center" style="width: 10%;"> Precio de compra </th>
                <th class="text-center" style="width: 10%;"> Precio de venta </th>
                <th class="text-center" style="width: 10%;"> Producto agregado </th>
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td>
                  <?php if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="../uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                    <img class="img-avatar img-circle" src="../uploads/products/<?php echo $product['image']; ?>" alt="">
                  <?php endif; ?>
                </td>
                <td> <?php echo remove_junk($product['name']); ?></td>
                <td class="text-center"> <?php echo !empty($product['brand']) ? remove_junk($product['brand']) : 'Sin marca'; ?></td>
                <td class="text-center"> <?php echo !empty($product['categorie']) ? remove_junk($product['categorie']) : 'Sin categoría'; ?></td>
                <td class="text-center">
                  <?php if($product['quantity'] == 0): ?>
                    <span style="color: red;">No disponible</span>
                  <?php else: ?>
                    <?php echo remove_junk($product['quantity']); ?>
                  <?php endif; ?>
                </td>
                <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="/RicPlast3.1/Controller/edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="/RicPlast3.1/Controller/delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                    <img src="../uploads/users/6.png" alt="Remove Icon" style="width:16px; height:16px;">
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
