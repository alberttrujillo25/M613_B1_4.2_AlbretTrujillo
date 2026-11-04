<?php
require_once __DIR__ . '/vots.php';

//Busca l'usuari per l'email.
function buscaUsuari(array $usuaris, string $email): ?array{
    foreach($usuaris as $usuari){
        if($usuari['email']===$email){
            return $usuari;
        }
    }

    return null;
}

//retorna el temps.
function tempsDesDe(int $moment): string{
    $diferencia= max(0, time()-$moment);
    $dies= floor($diferencia/86400);

    if($diferencia<60){
        return 'fa menys d\'un minut';
    }

    if($diferencia<3600){
        $minuts= floor($diferencia/60); //retorna el numero arrodonit en la versió més petita.
        return 'fa '. $minuts .' '.($minuts===1 ? 'minut':'minuts'); //segons singular o plural.
    }

    if($diferencia<86400){
        $hores= floor($diferencia / 3600);
        return 'fa '. $hores .' '.($hores===1 ? 'hora':'hores');
    }

    return 'fa '. $dies .' '.($dies===1 ? 'dia':'dies');
}

//comprova que la votació està tancada.
function votacioOberta(string $tancament): bool{
    $dataTancament= strtotime($tancament);
    return ($dataTancament!==false && time()<$dataTancament);
}

//comprova si un usuari ja ha votat.
function jaHaVotat(string $email): bool{
    $vots= llegeixVots();
    return array_key_exists($email, $vots);
}

//compta els vots de cada opció.
function recompte(array $vots, array $opcions): array{
    $resultats= [];

    //inicialitzo 'resultts'.
    foreach($opcions as $clau => $nomOpcio){ //per clau-valor.
        $resultats[$clau]= 0;
    }

    //compto vots.
    foreach($vots as $email => $opcio){
        if(array_key_exists($opcio, $resultats)){
            $resultats[$opcio]++;
        }
    }

    return $resultats;
}

//guarda missatge flash.
function afegeixMissatge(string $text): void{
    $_SESSION['flash']= $text;
}

//llegeix i elimina missatge flash.
function llegeixMissatge(): ?string{
    $missatge= $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return is_string($missatge) ? $missatge:null;
}