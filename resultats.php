<?php
session_start();
require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/funcions.php';
require_once __DIR__ . '/inc/vots.php';
$dades= require __DIR__ . '/inc/dades.php';

//recuperem les dades de l'usuari.
$usuari= $_SESSION['usuari'];
$email= $usuari['email'];
$rol= $usuari['rol'];

//recuperem les dades de la consulta.
$opcions= $dades['consulta']['opcions'];
$tancament= $dades['consulta']['tancament'];

//llegim els vots.
$vots= llegeixVots();

//comprovem si la votació està tancada.
$oberta= votacioOberta($tancament);

//comprovem si l'usuari ja ha votat.
$haVotat= jaHaVotat($email);

//comprovem si pot consultar els resultats.
if ($rol!=='admin' && !$haVotat&&$oberta){
    afegeixMissatge('Podràs veure els resultats quan hagis votat.');
    header('Location: votacio.php');
    exit;
}

//comptem els vots de cada opció.
$resultats= recompte($vots, $opcions);

//calculem el total de vots.
$totalVot = count($vots);

//calculem el nombre total de votants.
$totalVotants= 0;

foreach ($dades['usuaris'] as $usuariDada) {
    if ($usuariDada['rol']==='votant') {
        $totalVotants++;
    }
}

//carreguem la capçalera comuna.
require_once __DIR__ . '/inc/capcalera.php';
?>

<hr>

<h2>Resultats de la votació</h2>

<p><?=htmlspecialchars($dades['consulta']['pregunta'])?></p>

<table border="1">
    <thead>
        <tr>
            <th>Opció</th>
            <th>Vots</th>
            <th>Percentatge</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($opcions as $clau => $nomOpcio): ?>
            <?php
            $totalVots= 0;
            
            //calculem el percentatge de cada opció.
            $percentatge= $totalVots>0 ? round(($resultats[$clau]/$totalVots)*100): 0;?>

            <tr>
                <td><?= htmlspecialchars($nomOpcio) ?></td>
                <td><?= $resultats[$clau] ?></td>
                <td><?= $percentatge ?>%</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Participació</h3>

<p>
    Participació: <?= $totalVots ?> de <?= $totalVotants ?> votants
</p>

<hr>

<?php
require_once __DIR__ . '/inc/peu.php';
?>