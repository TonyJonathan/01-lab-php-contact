<?php
session_start(); 
$different = "";

   // Récupérer les données soumises
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(
        isset($_POST['csrf_token']) &&
        isset($_SESSION['csrf_token']) &&
        $_SESSION['csrf_token'] === $_POST['csrf_token']
    ) {

        $prenom = $_POST["prenom"]; 
        $nom = $_POST["nom"];
        $email = $_POST["email"]; 
        $password = $_POST["password"]; 
        $password_repeat = $_POST["password_repeat"]; 
        $sel = bin2hex(random_bytes(16)); // Générez un sel aléatoire sécurisé (16 octets ici)

        if($password === $password_repeat){

            try{
                // Établir une connexion à la base de données avec PDO
                $servername = "mysql:host=mysql";
                $username = getenv("MYSQL_USER");
                $password_db = getenv("MYSQL_PASSWORD");
                $dbname = getenv("MYSQL_DATABASE");

                $conn = new PDO("$servername;dbname=$dbname;charset=utf8", $username, $password_db);
                
                // Définir le mode d'erreur PDO sur exception (sert à gérer les erreurs plus facilement)
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Préparer une requête SQL pour insérer les données
                $sql = "INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, sel) values (:prenom, :nom, :email, :mot_de_passe, :sel)";
                $stmt = $conn->prepare($sql); 

                // Hacher le mot de passe avant de l'insérer dans la base de données (pour des raisons de sécurité)
                
                $hashed_password = password_hash($password . $sel, PASSWORD_DEFAULT); 

                // Lier les paramètres et exécuter la requête
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':nom', $nom); 
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mot_de_passe', $hashed_password); 
                $stmt->bindParam(':sel', $sel); 

                $stmt->execute(); 

            header('Location: index.php');
            exit();
            
            
            } catch (PDOExeption $e) {
                echo "Erreur dans l'inscription des données : " . $e->getMessage(); 
            }

            //Fermer la connection à la base de données 
            $conn = null;
        
        } else {
            $different = "error"; 
        }

    } else {
        echo 'CSRF error';
    }
}

$csrf_token = bin2hex(random_bytes(32)); 
$_SESSION['csrf_token'] = $csrf_token; 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Formulaire d'Inscription</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Formulaire d'Inscription</h2>
    <?php 
            if($different === "error" ){
                echo "<div class='alert alert-danger' role='alert'>
                Les mots de passe ne correspondent pas.
              </div>"; 
            }
        ?>
    <form action="" method="POST">
        <!-- Champ : Prénom -->
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>

        <!-- Champ : Nom -->
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>

        <!-- Champ : Adresse e-mail -->
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Champ : Mot de passe -->
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="" required>
        </div>

        <!-- Champ : Répéter le mot de passe -->
        <div class="form-group">
            <label for="password_repeat">Répéter le mot de passe</label>
            <input type="password" class="form-control" id="password_repeat" name="password_repeat" required>
        </div>

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Bouton d'envoi -->
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>

