<?php
require_once('../Model/load.php');
if (!$session->isUserLoggedIn(true)) { redirect('../index.php', false); }

if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  $product_name = $db->escape($_POST['product_name']);
  $products = find_product_by_title2($product_name);
  $html = '';

  if ($products) {
      foreach ($products as $product) {
          $price = isset($product['sale_price']) ? $product['sale_price'] : 0;
          $brand = !empty($product['brand']) ? $product['brand'] : 'Sin marca';
          $category = !empty($product['category_name']) ? $product['category_name'] : 'Sin categoría';

          error_log("ID: " . $product['id'] . " - Name: " . $product['name']);
          $html .= '<li class="list-group-item" onclick="selectProduct(' . $product['id'] . ', \'' . $product['name'] . '\', ' . $price . ', \'' . $brand . '\', \'' . $category . '\')">';
          $html .= $product['name'];
          $html .= "</li>";
      }
  } else {
      $html .= "<li class='list-group-item'>Producto no encontrado</li>";
  }

  echo $html;
  exit;
}

?>

 <?php
 // find all product
  if(isset($_POST['p_name']) && strlen($_POST['p_name']))
  {
    $product_title = remove_junk($db->escape($_POST['p_name']));
    if($results = find_all_product_info_by_title($product_title)){
        foreach ($results as $result) {

          $html .= "<tr>";

          $html .= "<td id=\"s_name\">".$result['name']."</td>";
          $html .= "<td id=\"s_brand\">".(!empty($result['brand']) ? $result['brand'] : 'Sin marca')."</td>";
          $html .= "<td id=\"s_category\">".(!empty($result['category_name']) ? $result['category_name'] : 'Sin categoría')."</td>";
          $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\">";
          $html  .= "</td>";
          $html .= "<td id=\"s_qty\">";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Add sale</button>";
          $html  .= "</td>";
          $html  .= "</tr>";

        }
    } else {
        $html ='<tr><td>El nombre del producto no se registra en la base de datos</td></tr>';
    }

    echo json_encode($html);
  }
 ?>

<?php
// Helper function to get category name by ID
function get_category_name($category_id) {
    global $db;
    $sql = "SELECT name FROM categories WHERE id = {$category_id} LIMIT 1";
    $result = find_by_sql($sql);
    return $result ? $result[0]['name'] : 'Sin categoría';
}

function find_product_by_title2($product_name) {
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
?>