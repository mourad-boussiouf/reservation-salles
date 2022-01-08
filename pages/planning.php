<?php 
require_once('classes/classCrenneau.php');
require_once('classes/classWeek.php');
require_once('classes/User.php');

$path_index="../index.php";
$path_inscription="inscription.php";
$path_connexion="connexion.php";
$path_profil="profil.php";
$path_planning="planning.php";
$path_booking="reservation.php";
$path_BookingForm="reservation-form.php";
?>

<?php
if(!isset($_SESSION['login'])){
include('header.html');
}
if (isset($_SESSION['login'])) {
include('loggedbar.php');
}
?>

<?php 
 session_start();

 date_default_timezone_set('Europe/Paris'); // fonction
//  var_dump(date_default_timezone_set('Europe/Paris')); 

$eventsFromDB = new Creneaux(); // pas encore créée
$tableCell = [];
$currentEvent = []; 

$actWeek = new Week($_GET['day'] ?? null, $_GET['month'] ?? null, $_GET['year'] ?? null);
// var_dump($actWeek); // GET POUR POUVOIR RECUPERER INFOS DANS RESERVATION.PHP
$startingDayWeek = $actWeek->getFirstday();
//modify permet de modifier l'objet "+5days -1 second"
$end =(clone $startingDayWeek)->modify('+ 5 days - 1 second'); 
// var_dump($startingDayWeek, $end);
// valeur de retour en faisant un var dump
// qui permet d'envoyer des conditions à partir de là 
// $eventsFromDB est un nouvel objet de la class creneau

// ça créée le décompte 
$events = $eventsFromDB -> getEventsBetweenByDayTime($startingDayWeek, $end); 
// ON COMMENCE A CREER LES BOUCLES POUR PARCOURIR LE TABLEAU
foreach ($events as $k => $event){
// $tableCell est un tableau multidimensionnel qui contient le descriptif de l'event(case) et sa durée(début,fin)
    $tableCell[$event['case']] = $event['length']; 
}
?>
<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="../CSS/planning.css">
<body>
    <main>
    <div class="calendarnav">
    <!-- on applique la methode previous week sur les jours -->
        <a href="planning.php?day=<?= $actWeek->previousWeek()->day;?>&month=<?= $actWeek->previousWeek()->month; ?> &year=<?= $actWeek->previousWeek()->year; ?>"></a>
        <h1>Planning: <?= $actWeek->ToString(); ?></h1>
        <a href="planning.php?day=<?= $actWeek->nextWeek()->day;?>&month=<?= $actWeek->nextWeek()->month; ?> &year=<?= $actWeek->nextWeek()->year; ?>"></a>
    </div>
    <table>

    <!-- colgroup permet de spécifier une colonne ou groupe de colonnes dans une table -->
    <colgroup>
        <col>
        <col span="5">
        <col span="2">
    </colgroup>

    <?php 
    // GENERATION DU TABLEAU 
    //BOUCLE POUR LIGNES // 11H
    for ($y=0; $y < 12; $y++) { 
        echo '<tr>', "\n";
        // BOUCLE POUR COLONNES (jours)
        for ($x = 0; $x < 8; $x++) { 
            //coordinate = équation des 2 boucles ()
            $coordinate = $y . '-' . $x;
            $cellLength = null; 

            // ON SET LA CELLULE HORAIRES
            if ($y == 0 && $x == 0)
            echo '<th>Horaires</th>';
            // ON SET LES JOURS
            // si y==0 les heures sont réinitialisés et on passe au jour suivant 
            elseif ($y == 0 && $x > 0) {
                    $daysNumber = $actWeek->mondayDate + $x - 1;
                    echo '<th>' . $actWeek->getDays ($x -1) . ' ' . $daysNumber . '</th>';
            }
            // ON SET LES HEURES 
            //si x==0 on passe à une autre semaine
            elseif ($y > 0 && $x == 0) {
                $tempHour = 7 + $y; 
                if ($tempHour < 10) {
                   $hour = '0' . $tempHour . ':00'; // hypothèse : réservation max de 9h
                }
                else {
                    $hour = $tempHour . ':00'; 
                }
                echo '<th>' . $hour . '</th>';
            }
            // si la value contenue à l'heure de key dans le tableau $tableCell = l'heure actuelle de la boucle (coordinate)
            // alors on imprime la value dans la case 
            else {
                foreach($tableCell as $key => $value) {
                    if ($coordinate === $key) {
                        $cellLength = $value;
                    }
                } 
                // TROUVER ROWSPAN S'IL EXISTE
                foreach ($tableCell as $key => $value) {
                    if ($coordinate === $key) {
                        $cellLength = $value; 
                    }
                }
                // TROUVER L'EVENT S'IL EXISTE
                foreach ($events as $k => $event) {
                    if ($coordinate == $event['case']) {
                        $currentEvent = $event;
                    }
                } 
                if (isset($cellLength) && $cellLength !== FALSE) {
                    //fusion des cellules en fonction du temps de l'event 
                    echo '<td rowspan="'. $cellLength .'"';
                    echo ' style="color:white;text-shadow: 1px 1px 1px black; background-color:' . '">';
                    echo "<a href=\"reservation.php?id=" . $currentEvent['id'] . '" class=table_link>';
                    echo '<span>' . $currentEvent['login'] . '</span>', '<br/>'; 
                    echo '</a>'; 
                    echo '</td>';

                    //tant que la reservation fait plus d'une heure on dit que la case d'en dessous = FALSE
                    $tempY = $y + 1; 
                    while ($cellLength > 1) {
                       $tableCell[$tempY . '-' . $x] = FALSE; 
                       // on se prépare à checker la case d'en dessous
                       $tempY++; 
                       $cellLength--;
                    }
                }     
                else {
                    if (isset($tableCell[$coordinate])){
                        ;
                }
                else {
                    echo'<td></td>'; 
                }
            }
        }
    }
    echo '</tr>', "\n"; 
    }
    ?>
    </table>
    
    <a href="reservation.php">Consulter les réversations</a> <a href="reservation-form.php">Faire une demande de réservation</a>

    </main>
<footer>
    <?php
     require_once('footer.html'); 
    ?>
</footer>
</body>
</html>


<link rel="stylesheet" href="CSS/planning.css">

<?php
$path_img_footer1 = '../images/logobbYellow.png';
$path_img_footer2 ='../images/logotomate.PNG';
$path_footer='CSS/footer.css';
?>