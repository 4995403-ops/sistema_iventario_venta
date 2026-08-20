<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $empresa = trim($_POST['empresa']);
    $contacto = trim($_POST['contacto']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    try {
        $sql = "INSERT INTO proveedores (nombre_empresa, contacto, telefono, direccion) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $empresa, $contacto, $telefono, $direccion);
        $stmt->execute();
        $stmt->close();

        header("Location: proveedores.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        die("Error crítico al registrar el proveedor: " . $e->getMessage());
    }
} else {
    header("Location: proveedores.php");
    exit();
}
?>
