<?php
require_once('../Model/load.php');

if (isset($_POST['product_id'])) {
    $product_id = $db->escape((int)$_POST['product_id']);
    $product = find_by_id('products', $product_id);

    if ($product) {
        echo $product['quantity'];
    } else {
        echo "N/A";
    }
}
?>
