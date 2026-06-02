<?php
$hostname = "localhost";
$username = "root";      // Usuario por defecto de XAMPP
$password = "";          // Contraseña por defecto de XAMPP (vacía)
$database = "sistema_inventario"; // Reemplaza por el nombre EXACTO de tu base de datos

// Crear la conexión física
$conn = new mysqli($hostname, $username, $password, $database);

// Validar si la conexión falló
if ($conn->connect_error) {
    die("Fallo crítico en la conexión: " . $conn->connect_error);
}

// Configurar caracteres en UTF-8 para evitar problemas con tildes o eñes
$conn->set_charset("utf8");
?>