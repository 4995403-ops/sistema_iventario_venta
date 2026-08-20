<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario_id = $_SESSION['user_id'];
    $proveedor_id = $_POST['proveedor_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $precio_compra = $_POST['precio_compra'];

    $total_compra = $cantidad * $precio_compra;

    try {
        // FASE 1: INSERTAR MAESTRO (Cabecera)
        $sql_compras = "INSERT INTO compras (proveedor_id, usuario_id, total) VALUES (?, ?, ?)";
        $stmt1 = $conn->prepare($sql_compras);
        $stmt1->bind_param("iid", $proveedor_id, $usuario_id, $total_compra);
        $stmt1->execute();

        $id_nueva_compra = $conn->insert_id;
        $stmt1->close();

        // FASE 2: INSERTAR DETALLE (Líneas)
        $sql_detalle = "INSERT INTO detalle_compras (compra_id, producto_id, cantidad, precio_compra) VALUES (?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql_detalle);
        $stmt2->bind_param("iiid", $id_nueva_compra, $producto_id, $cantidad, $precio_compra);
        $stmt2->execute();
        $stmt2->close();

        header("Location: dashboard.php");
        exit();

    } catch (mysqli_sql_exception $e) {
        die("Error crítico en la transacción Maestro-Detalle: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>