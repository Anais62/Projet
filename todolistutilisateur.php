<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connexionBDD.php');

// Ouverture de la session

session_start();
$id = $_SESSION['id'];
$prenom = $_SESSION["prenom"];



if (!empty($_POST['usertache'])){

// On récupére les tâches et en les stockants dans une variable

$tache= $_POST["usertache"];

// Préparation de la requête
    $requete = 'INSERT INTO liste (tache,id_user) VALUES (:tache,:iduser)';

 // Création d'un objet PDOStatement
    $query = $db->prepare($requete);

 // Association d'une valeur à un paramètre de l'objet PDOStatement
    $query->bindValue(':tache', $tache, PDO::PARAM_STR);
    $query->bindValue(':iduser', $id, PDO::PARAM_STR);

// Exécution de la requête
    $query->execute();

// Fermeture du curseur : la requête peut être de nouveau exécutée
    $query->closeCursor();
     
}

// Pour se déconnecter via le bouton

if(isset($_POST['deconnexion'])) {
    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion
    header("Location: pagedeco.php");
    exit;
}

// Pour supprimer les tâches séléctionner dans checkBox

// Vérifier si le bouton "supprimer" a été soumis dans le formulaire
if (isset($_POST['supprimer'])) {
    // Récupérer les ID des tâches à supprimer depuis le tableau POST
    $tachesASupprimer = isset($_POST['tache_checkbox']) ? $_POST['tache_checkbox'] : array();

    // Vérifier si des tâches ont été sélectionnées
    if (!empty($tachesASupprimer)) {
        // Requête SQL pour la suppression d'une tâche par ID
        $requeteSuppression = 'DELETE FROM liste WHERE id = :id';

        // Boucle à travers les ID des tâches à supprimer
        foreach ($tachesASupprimer as $idTache) {
            // Préparer la requête de suppression
            $querySuppression = $db->prepare($requeteSuppression);

            // Associer la valeur de l'ID à supprimer dans la requête
            $querySuppression->bindValue(':id', $idTache, PDO::PARAM_INT);

            // Exécuter la requête de suppression pour chaque ID
            $querySuppression->execute();

            // Fermer le curseur pour libérer les ressources
            $querySuppression->closeCursor();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

    <script>
        function supprimerTaches() {
            let formulaireSuppression = document.getElementById('formSuppression');
            formulaireSuppression.submit();
        }
    </script>
    
</head>
<body>

<h1>Bonjour <?php echo $_SESSION['prenom']; ?></h1>

<form action="" method="POST">
        
    <label for="tache">Entrez votre tache</label>
    <input class="formulaire" type="tache" name="usertache">

    <button type="submit">Ok</button>   

</form>

<form class="form2" action="" method="post">

    <button id="deconnexion" type="submit" name="deconnexion">Se déconnecter</button>

</form>

<form class="form3" id="formSuppression" action="" method="post">
        <?php
        $sql = "SELECT * FROM liste WHERE id_user = :iduser";
        $query = $db->prepare($sql);
        $query->bindValue(':iduser', $id, PDO::PARAM_INT);
        $query->execute();

        // Boucle pour incrémanter chaque tâche à la suite avec un checkBox

        while ($ligne = $query->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class=check>" . "<br>" . "<input type='checkbox' name='tache_checkbox[]' value='" . $ligne['id'] . "'>" . $ligne["tache"] . "</div>";
        }
        ?>
        <button id="supprimer" type="submit" name="supprimer" onclick="supprimerTaches()">Supprimer les tâches</button>
    </form>

</body>
</html>