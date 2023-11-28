<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connexionBDD.php');

// On récupère les données envoyées par le formulaire
if (!empty($_POST['useremailco']) && !empty($_POST['usermdpco'])) {
    $emailco = $_POST['useremailco'];
    $mdpco = $_POST['usermdpco'];

    // Vérification de l'e-mail
    $sql = "SELECT * FROM `user` WHERE email = :email";
    $query = $db->prepare($sql);
    $query->bindValue(":email", $emailco, PDO::PARAM_STR);
    $query->execute();
    $verifEmail = $query->fetch(PDO::FETCH_ASSOC);
    var_dump($verifEmail);

    // Vérification du mot de passe
    if ($verifEmail && password_verify($mdpco, $verifEmail['mdp'])) {
        echo "Connexion réussie";

        // Ouverture de la session

        session_start();
        $_SESSION["id"] = $verifEmail['id'];
        $_SESSION["prenom"] = $verifEmail['prenom'];
        echo "Bonjour ".$verifEmail['prenom'];

        // Redirection vers la page de l'utilisateur

        header("Location: todolistutilisateur.php");
        
        exit();
    } else {
        echo "Adresse e-mail ou mot de passe incorrect";
    }
}
if (isset($_POST['deja'])) {
    // Redirection vers la page d'inscription
    header("Location: pageinscription.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Connexion</h1>
    <form action="" method="POST">
        <div>
            <label for="emailco">Entrez votre adresse mail:</label>
            <input class="formulaire" type="email" name="useremailco">
        </div> 

        <br>
         
        <div>
            <label for="mdpco">Entrez votre mot de passe:</label>
            <input class="formulaire" type="password" id="mdp" name="usermdpco">
        </div>

        <div>
            <button type="submit">Connexion </button>
        </div>
    
        <div>
        <button id="deja" name="deja" type="submit">Pas encore inscrit ?</button>
        </div>
      
    </form>

</body>
</html>
