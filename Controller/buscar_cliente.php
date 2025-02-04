<?php
require_once('../Model/load.php');
page_require_level(2);

if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    $nombre = $db->escape($_GET['nombre']);
    $sql = "SELECT id, name FROM clients WHERE name LIKE '%$nombre%' LIMIT 10";
    $result = find_by_sql($sql);
    foreach ($result as $cliente) {
        echo "<a href='#' class='list-group-item' onclick='seleccionarCliente(" . $cliente['id'] . ", \"" . $cliente['name'] . "\")'>" . $cliente['name'] . "</a>";
    }
}
?>
