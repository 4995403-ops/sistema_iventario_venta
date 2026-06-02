<?php
// 1. Iniciar el motor de sesiones de PHP
session_start();

// 2. Incluir la conexión oficial de MySQLi
require_once 'conexion.php';

// 3. Validar que la información provenga del formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener y limpiar espacios en blanco de los datos
    $user = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    try {
        // 4. Diseñar la consulta con marcadores de posición (?) para seguridad
        $sql = "SELECT id, nombre_completo, password, rol FROM usuarios WHERE usuario = ?";
        
        // 5. Preparar la sentencia en el servidor
        $stmt = $conn->prepare($sql);
        
        // 6. Vincular el parámetro de usuario como string ("s")
        $stmt->bind_param("s", $user);
        
        // 7. Ejecutar de forma segura
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();

        // 8. Verificar si encontramos exactamente un usuario
        if ($result->num_rows === 1) {
            // Extraer los datos en un arreglo asociativo
            $row = $result->fetch_assoc();
            
            // 9. Validar si la contraseña coincide (Temporalmente en texto plano)
            if ($password === $row['password']) {
                
                // Credenciales correctas: Guardamos datos en la Sesión
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nombre'] = $row['nombre_completo'];
                $_SESSION['rol'] = $row['rol'];
                
                // Redirigir al Dashboard
                header("Location: test_dashboard.php");
                exit();
            } else {
                // Contraseña incorrecta
                header("Location: index.php?error=1");
                exit();
            }
        } else {
            // Usuario no encontrado
            header("Location: index.php?error=1");
            exit();
        }

        // Cerrar la sentencia de forma ordenada
        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        // Detener el script si hay un fallo crítico de SQL
        die("Error de autenticación en el servidor: " . $e->getMessage());
    }
} else {
    // Si intentan entrar directo a la URL, los devolvemos al Login
    header("Location: index.php");
    exit();
}
?>