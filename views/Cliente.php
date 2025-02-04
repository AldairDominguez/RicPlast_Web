<?php
require_once('../Model/load.php');

class Cliente {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Método para guardar la información del cliente
    public function guardarCliente($name, $dni, $address, $email) {
        $query = "INSERT INTO clients (name, dni, address, email) VALUES ('{$this->db->escape($name)}', '{$this->db->escape($dni)}', '{$this->db->escape($address)}', '{$this->db->escape($email)}')";
        if($this->db->query($query)){
            return $this->db->insert_id();
        }
        return null;
    }

    // Método para verificar si el producto tiene ventas registradas
    public function verificarVentasProducto($product_id) {
        $sales = find_by_sql("SELECT * FROM sales WHERE product_id = {$this->db->escape((int)$product_id)}");
        return !empty($sales);
    }

    // Método para generar la boleta de venta
    public function generarBoleta($venta_id) {
        $venta = find_sale_by_id($venta_id); // Función que debes implementar para obtener los datos de la venta
        $productos = find_products_by_sale_id($venta_id); // Productos de la venta

        echo "<h1>Boleta de Venta</h1>";
        echo "Fecha: " . $venta['date'] . "<br>";
        echo "Cliente: " . $venta['client_name'] . "<br>";
        echo "DNI: " . $venta['client_dni'] . "<br>";
        echo "Dirección: " . $venta['client_address'] . "<br>";
        
        echo "<table>";
        echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Total</th></tr>";
        $total = 0;
        foreach ($productos as $producto) {
            $subtotal = $producto['qty'] * $producto['price'];
            echo "<tr>";
            echo "<td>{$producto['name']}</td>";
            echo "<td>{$producto['qty']}</td>";
            echo "<td>{$producto['price']}</td>";
            echo "<td>" . $subtotal . "</td>";
            echo "</tr>";
            $total += $subtotal;
        }
        echo "</table>";
        echo "<p>Total a Pagar: S/. " . number_format($total, 2) . "</p>";
    }
}
