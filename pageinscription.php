<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connexionBDD.php');

//On recupere les données envoyées par le formulaire


if (!empty($_POST['usernom']) && !empty($_POST['userprenom']) && !empty($_POST['useremail']) && !empty($_POST['usermdp']) && !empty($_POST['usermdp2'])) {
    
    // On récupére les infos du formulaire et en les stockants dans des variables 

    $nom = $_POST['usernom'];
    $prenom = $_POST['userprenom'];
    $email = $_POST['useremail'];
    $mdp = $_POST['usermdp'];
    $mdp2 = $_POST['usermdp2'];
   
    //On verifie si l'adresse mail est déjà utilisé
    
    $sql = "SELECT * FROM `user` WHERE email = :email";
            $query = $db->prepare($sql);
            $query->bindValue(":email", $email, PDO::PARAM_STR);
            $query->execute();
            $verifEmail = $query->fetch();
        var_dump($verifEmail);

    // Si l'adresse mail n'est pas dans la bdd on rentre dans la condition sinon incorrecte
        
    if($verifEmail === false) {

        // Vérification des 2 mots de passe identiques 

        if ($mdp === $mdp2) {
            // Hachage du mot de passe
            $motdepassehash = password_hash($mdp, PASSWORD_DEFAULT);

            // Préparation de la requête
            $requete = 'INSERT INTO user (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)';

            // Création d'un objet PDOStatement
            $query = $db->prepare($requete);

            // Association d'une valeur à un paramètre de l'objet PDOStatement
            $query->bindValue(':nom', $nom, PDO::PARAM_STR);
            $query->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->bindValue(':mdp', $motdepassehash, PDO::PARAM_STR);

            // Exécution de la requête
            $query->execute();

            // Fermeture du curseur : la requête peut être de nouveau exécutée
            $query->closeCursor();
            
            // redirection vers la page de co 

            header("Location: pagedeco.php");

        } else {
            echo 'Les mots de passe ne correspondent pas. Veuillez réessayer.';
        }
    } else {
        echo "Adresse e-mail incorrecte";
    }
 }
 if (isset($_POST['dejainscrit'])) {
    // Redirection vers la page d'inscription
    header("Location: pagedeco.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<form action="" method="POST">
        
        <h1>Formulaire d'inscription</h1>
    
        <br>

        <div>
        <label for="nom">Entrer votre nom :</label>
        <input class="formulaire" type="text" id="nom" name="usernom">
        </div>
        
        <br>

        <div>
        <label for="prenom">Entrer votre prenom :</label>
        <input class="formulaire" type="text" id="prenom" name="userprenom">
        </div> 

        <br>

        <br>

        <div>
        <label for="email">Entrer votre adresse mail :</label>
        <input class="formulaire" type="email" name="useremail">
        </div> 

        <br>
         
        <div>
         <label for="mdp">Entrer votre mot de passe :</label>
         <input class="formulaire" type="password" id="mdp" name="usermdp">
        </div>
        <br>
        
        <label for="mdp2">Entrer de nouveau votre mot de passe :</label>
        <input class="formulaire" type="password" name="usermdp2">

        <div>
         <button type="submit">Inscription </button>
        </div>
        <div>
         <button id="dejainscrit" name="dejainscrit" type="submit">Déjà inscrit </button>
        </div>
    </form> 

</body>
</html>

