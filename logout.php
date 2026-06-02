<?php
// Iniciar el motor para saber qué sesión destruir
session_start();

// Borrar variables de sesión
session_unset(); 

// Destruir la sesión físicamente en el servidor
session_destroy(); 

// Redirigir al inicio
//  (Login)
header("Location: index.php");
exit();
?>