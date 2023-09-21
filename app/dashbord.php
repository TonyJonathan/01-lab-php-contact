<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Vérifiez si l'adresse e-mail de l'utilisateur est stockée dans la session
if($_SERVER['REQUEST_METHOD'] == "POST"){
$nom = $_POST['nom']; 
$prenom = $_POST['prenom'];
$email = $_POST['email']; 

// Démarrez la session (assurez-vous de l'appeler avant d'utiliser $_SESSION)
session_start(); 
if(isset($_SESSION['user_id'])){
    
    // Récupérez l'id de l'utilisateur depuis la session
    $user_id = $_SESSION['user_id']; 
}
    try{
        // Établir une connexion à la base de données avec PDO
        $servername = "mysql:host=mysql";
        $username = getenv("MYSQL_USER");
        $password_db = getenv("MYSQL_PASSWORD");
        $dbname = getenv("MYSQL_DATABASE");

        $conn = new PDO("$servername;dbname=$dbname;charset=utf8", $username, $password_db);
        
        // Définir le mode d'erreur PDO sur exception (sert à gérer les erreurs plus facilement)
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT into contacts (nom, prenom, email, utilisateur_id) values (:nom, :prenom, :email, :utilisateur_id)"; 
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':nom', $nom); 
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email); 
        $stmt->bindParam(':utilisateur_id', $user_id); 
        $stmt->execute();
    } catch (PDOExeption $e) {
        echo "Erreur dans l'inscription des données : " . $e->getMessage(); 
    }

    //Fermer la connection à la base de données 
    $conn = null;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Contacts</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Mon Tableau de Bord</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">            
            <li class="nav-item">
                <a class="nav-link" href="http://php-dev-1.online/">Se déconnecter</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Tableau de Bord - Gestion des Contacts</h2>
    <p>Bienvenue dans votre tableau de bord de gestion des contacts. Vous pouvez ajouter, modifier ou supprimer des contacts ici.</p>
    <div class="row">
        <div class="col-md-6">
            <!-- Formulaire d'ajout de contact -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter Contact</button>
            </form>
        </div>
        <div class="col-md-6">
            <!-- Liste des contacts -->
            <h3>Liste des Contacts</h3>
            <ul class="list-group">
                <li class="list-group-item">John Doe - john.doe@example.com</li>
                <li class="list-group-item">Jane Smith - jane.smith@example.com</li>
                <li class="list-group-item">Michael Johnson - michael.johnson@example.com</li>
            </ul>
        </div>
    </div>
</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
