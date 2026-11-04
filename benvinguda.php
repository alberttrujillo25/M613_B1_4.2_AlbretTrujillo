<?php
session_start();
require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/funcions.php';
$dades= require __DIR__ . '/inc/dades.php';

//obtenim les dades de l'usuari.
$usuari= $_SESSION['usuari'];
$email= $usuari['email'];
$rol= $usuari['rol'];

//comprovem si la votació està oberta.
$tancament= $dades['consulta']['tancament'];
$oberta= votacioOberta($tancament);

//comprovem si l'usuari ja ha votat.
$haVotat= jaHaVotat($email);

//preparem la data de tancament.
$dataTancament= date('d/m/Y \a \l\e\s H:i', strtotime($tancament));

//calculem quant temps fa que ha iniciat sessió.
$tempsLogin= tempsDesDe($_SESSION['login'] ?? time());

//carreguem la capçalera comuna.
require_once __DIR__ . '/inc/capcalera.php';
?>

<hr>

<h2>Hola, <?= htmlspecialchars($usuari['nom']) ?>!</h2>

<p>Has iniciat sessió <?= htmlspecialchars($tempsLogin) ?>.</p>

<h3>Consulta</h3>

<p><?=htmlspecialchars($dades['consulta']['pregunta'])?></p>

<p>Data de tancament:<?=htmlspecialchars($dataTancament)?></p>

<?php if($oberta):?>
    <p>La votació està oberta.</p>
<?php else:?>
    <p>La votació està tancada.</p>
<?php endif;?>

<?php if($rol==='admin'):?>
    <p>Pots consultar els resultats.</p>
    <a href="resultats.php">Veure resultats</a>
<?php elseif($rol==='votant'):?>
    <?php if($haVotat):?>
        <p>Ja has votat!</p>
        <a href="resultats.php">Veure resultats</a>
    <?php elseif($oberta):?>
        <a href="votacio.php">Votar ara</a>
    <?php else:?>
        <p>La votació està tancada i ja no pots votar.</p>
        <a href="resultats.php">Veure resultats</a>
    <?php endif;?>
<?php endif;?>

<hr>

<?php
require_once __DIR__ . '/inc/peu.php';
?>