<?php
session_start();

$dades= require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

//si ja hi ha una sessió iniciada, redirigeix a la pàgina principal.
if(isset($_SESSION['usuari'])){
    header('Location: benvinguda.php');
    exit;
}

//configuració cookies.
$opcionsCookie= [
    'expires'=> time()+(30 * 24 * 60 * 60),
    'path'=> '/',
    'httponly'=> true,
    'samesite'=> 'Lax',
    'secure'=> !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off'
];

//cookie de darrera visita
$darreraVisita= $_COOKIE['darrera_visita'] ?? null;

$timestamp= is_string($darreraVisita) ? filter_var($darreraVisita, FILTER_VALIDATE_INT): false;

//creem missatge.
if($timestamp!==false && $timestamp>0 && $timestamp<=time()){ //comprovem que sigui un timestamp positiu i no futur.
    $missatgeVisita= 'Benvingut! La teva darrera visita va ser '.tempsDesDe($timestamp) .'.';
}else{
    $missatgeVisita= 'Benvingut! És la teva primera visita.';
}

//actualitzem cookie.
setcookie('darrera_visita', (string)time(), $opcionsCookie);

//recordar email.
$emailRecordat= $_COOKIE['email_recordat'] ?? '';

if(!is_string($emailRecordat) || filter_var($emailRecordat, FILTER_VALIDATE_EMAIL)===false){
    $emailRecordat= '';
}

//errors per GET.
$errors = [
    'credencials'=> 'Credencials no vàlides.',
    'acces'=> 'Primer has d\'iniciar sessió.',
    'caducada'=> 'La sessió ha caducat per inactivitat.'
];

$codiError= $_GET['error'] ?? '';
$missatgeError= $errors[$codiError] ?? null;

//processament del formulari POST
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email= trim($_POST['email'] ?? '');
    $contrasenya= $_POST['contrasenya'] ?? '';
    $recordaEmail= isset($_POST['recorda_email']);

    //gestionem la cookie de recordar email.
    if($recordaEmail && filter_var($email, FILTER_VALIDATE_EMAIL)){
        setcookie('email_recordat', $email, $opcionsCookie);
    }else{//esborrem la cookie si no s'ha marcat la casella.
        setcookie('email_recordat', '', [...$opcionsCookie, 'expires'=>time()-3600]);
    }

    //busquem l'usuari pel seu email.
    $usuari= buscaUsuari($dades['usuaris'], $email);

    //comprovem les credencials.
    if($usuari!==null && $usuari['contrasenya']===$contrasenya){
        //regenerem l'identificador de sessió.
        session_regenerate_id(true);

        //eliminem la contrasenya abans de guardar l'usuari.
        unset($usuari['contrasenya']);

        //guardem les dades a la sessió.
        $_SESSION['usuari']= $usuari;
        $_SESSION['login']= time();
        $_SESSION['activitat']= time();

        header('Location: benvinguda.php');
        exit;
    }else{
        header('Location: index.php?error=credencials');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <p><?= htmlspecialchars($missatgeVisita) ?></p>

    <?php if ($missatgeError !== null): ?>
        <p><?= htmlspecialchars($missatgeError) ?></p>
    <?php endif; ?>

    <h2>Iniciar sessió</h2>

    <!--formulari de login-->
    <form method="POST" action="index.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?=htmlspecialchars($emailRecordat)?>" required>
        <br><br>

        <label for="contrasenya">Contrasenya:</label>
        <input type="password" id="contrasenya" name="contrasenya" required>
        <br><br>

        <label>
            <input type="checkbox" name="recorda_email" <?= $emailRecordat !== '' ? 'checked' : '' ?>>
            Recorda el meu email
        </label>
        <br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>