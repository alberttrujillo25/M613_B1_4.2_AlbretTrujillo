<?php
session_start();

$nomUsuari= $_SESSION['usuari']['nom'] ?? 'usuari'; //guardem el nom abans de buidar la sessió.
$_SESSION= []; //buidem les dades de la sessió.
$parametres= session_get_cookie_params();//obtenim les opcions de la cookie de sessió.

//esborrem la cookie de sessió.
setcookie(session_name(), '', [
    'expires'=> time() - 42000,
    'path'=> $parametres['path'],
    'domain'=> $parametres['domain'],
    'secure'=> $parametres['secure'],
    'httponly'=> $parametres['httponly'],
    'samesite'=> $parametres['samesite'] ?? 'Lax'
]);

//tanquem sessió.
session_destroy();
?>

<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logout</title>
    </head>
    <body>
        <h1>Has tancat la sessió</h1>

        <p>Fins la propera, <?= htmlspecialchars($nomUsuari) ?>!</p>

        <a href="index.php">Tornar a entrar</a>
    </body>
</html>