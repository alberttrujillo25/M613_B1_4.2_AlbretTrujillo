<?php
session_start();
require_once __DIR__ . '/inc/protegeix.php';
require_once __DIR__ . '/inc/funcions.php';
$dades= require __DIR__ . '/inc/dades.php';

//recuperem les dades de l'usuari.
$usuari= $_SESSION['usuari'];
$email= $usuari['email'];
$rol= $usuari['rol'];

//recuperem la pregunta i les opcions.
$pregunta= $dades['consulta']['pregunta'];
$opcions= $dades['consulta']['opcions'];
$tancament= $dades['consulta']['tancament'];

//comprovem que sigui un votant.
if($rol!=='votant'){
    afegeixMissatge('Només els votants poden votar.');
    header('Location: benvinguda.php');
    exit;
}

//comprovem que la votació estigui oberta.
if(!votacioOberta($tancament)){
    afegeixMissatge('La votació està tancada.');
    header('Location: resultats.php');
    exit;
}

//comprovem si ja ha votat.
if(jaHaVotat($email)){
    afegeixMissatge('Ja has votat. Gràcies!');
    header('Location: resultats.php');
    exit;
}

//missatge d'error del formulari.
$errorFormulari= null;

//processem el vot rebut per POST.
if($_SERVER['REQUEST_METHOD']==='POST'){
    $opcioSeleccionada= $_POST['opcio'] ?? '';

    //comprovem que sigui una opció vàlida.
    if(!is_string($opcioSeleccionada) || !array_key_exists($opcioSeleccionada, $opcions)){
        $errorFormulari= 'Tria una de les opcions.';
    }else{
        //guardem el vot.
        guardaVot($email, $opcioSeleccionada);
        afegeixMissatge('Vot registrat. Gràcies per participar!');

        //redirigim als resultats.
        header('Location: resultats.php');
        exit;
    }
}

//carreguem la capçalera.
require_once __DIR__ . '/inc/capcalera.php';
?>

<h2>Votació</h2>

<p><?=htmlspecialchars($pregunta)?></p>

<?php if($errorFormulari!==null):?>
    <p><?=htmlspecialchars($errorFormulari)?></p>
<?php endif;?>

<form method="POST" action="votacio.php">
    <?php foreach($opcions as $clau=>$nomOpcio):?>
        <label>
            <input type="radio" name="opcio" value="<?=htmlspecialchars($clau)?>"required>
            <?=htmlspecialchars($nomOpcio)?>
        </label>
        <br>
    <?php endforeach;?>
    <br>

    <button type="submit">Enviar</button>
</form>

<?php
require_once __DIR__ . '/inc/peu.php';
?>