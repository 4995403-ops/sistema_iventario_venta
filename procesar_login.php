<?php
session_start();

// Conectamos a la base de datos usando el archivo limpio de arriba
require_once 'conexion.php';

// Capturamos los datos que vienen del formulario
$usuario_form = $_POST['usuario']; 
$password_form = $_POST['password'];

// Preparamos la consulta de forma segura (sin inyección SQL)
$sql = "SELECT id, nombre_completo, usuario, password, rol FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_form);
$stmt->execute();
$resultado = $stmt->get_result();

// Validamos si encontró al usuario
if ($resultado->num_rows > 0) {

    $fila = $resultado->fetch_assoc();

    // Comparamos la contraseña escrita contra el hash guardado en la BD
    if (password_verify($password_form, $fila['password'])) {

        $_SESSION['user_id'] = $fila['id'];
        $_SESSION['nombre'] = $fila['nombre_completo'];
        $_SESSION['rol'] = $fila['rol'];

        header("Location: dashboard.php");
        exit();

    } else {
        echo "<script>
                alert('Credenciales incorrectas. Verifica tu usuario y contraseña.');
                window.location.href = 'index.php';
              </script>";
    }

} else {
    echo "<script>
            alert('Credenciales incorrectas. Verifica tu usuario y contraseña.');
            window.location.href = 'index.php';
          </script>";
}

$stmt->close();
?>