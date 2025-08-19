<?php
//iniciar la sesión para poder acceder a ella
session_start();

//eliminar todas las variables de sesión
$_SESSION = [];

//destruir la sesión
session_destroy();

//eliminar cookies asociadas, si existen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"]
    );
}

//redirigir al índice (login)
header('Location: ../index.php');
exit();
