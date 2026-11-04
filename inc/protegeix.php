<?php
//comprovem si hi ha un usuari iniciat.
if(!isset($_SESSION['usuari'])){
    header('Location: index.php?error=acces');
    exit;
}

//comprovem temps de l'última activitat.
$ultimaActivitat= $_SESSION['activitat'] ?? 0;

//límit d'inactivitat: 5 minuts(300 segons).
$limitInactivitat= 300;

//si ha superat el límit, tanquem sessió.
if(!is_int($ultimaActivitat) || $ultimaActivitat<=0 || (time()-$ultimaActivitat)>$limitInactivitat){
    $_SESSION= [];
    session_destroy();
    header('Location: index.php?error=caducada');
    exit;
}

//actualitzem l'hora de l'última activitat.
$_SESSION['activitat']= time();
?>