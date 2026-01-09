<?php
session_start();

$USUARIO_VALIDO = "admin";
$HASH_PASSWORD_VALIDO = '$2y$10$udtxd9TRiFyq6H.9zm.ah.68rx.WlC0.oUPa/0yi3wRnLP7seCLL2';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $usuario_ingresado = $_POST['username'];
    $password_ingresada = $_POST['password'];

    if ($usuario_ingresado === $USUARIO_VALIDO && password_verify($password_ingresada, $HASH_PASSWORD_VALIDO)) {
        
        session_regenerate_id(true); 

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $usuario_ingresado;

        header("Location: index.php");
        exit;
    }
    else {
        header("Location: login.html");
        exit;
    }
}
else {
    header("Location: login.html");
    exit;
}
?>