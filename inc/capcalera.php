<?php
//obtenim el nom de l'usuari i el seu rol.
$nomUsuari= htmlspecialchars($usuari['nom'] ?? 'Usuari');
$rolUsuari= $usuari['rol'] ?? '';

//llegim el missatge flash de la sessió.
$missatge= $_SESSION['flash'] ?? null;

//eliminem el missatge perquè només es mostri una vegada.
unset($_SESSION['flash']);
?>

<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tasca 4.2</title>
    </head>
    <body>
        <header>
            <nav>
                <p><?= $nomUsuari ?></p>

                <a href="benvinguda.php">Inici</a>

                <?php if ($rolUsuari === 'votant'): ?>
                    <a href="votacio.php">Votar</a>
                <?php endif; ?>

                <a href="resultats.php">Resultats</a>

                <a href="logout.php">Tancar sessió</a>
            </nav>

            <?php if ($missatge !== null): ?>
                <p><?= htmlspecialchars($missatge) ?></p>
            <?php endif; ?>
        </header>

        <main>