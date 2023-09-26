<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$email = "";  
if(
    isset($_SESSION['csrf_token']) &&
    isset($_POST['csrf_token']) && 
    $_SESSION['csrf_token'] === $_POST['csrf_token']
) {

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST["email"]; 


        try{
            // Établir une connexion à la base de données avec PDO
            $servername = "mysql:host=mysql";
            $username = getenv("MYSQL_USER");
            $password_db = getenv("MYSQL_PASSWORD");
            $dbname = getenv("MYSQL_DATABASE");
            

            $conn = new PDO("$servername;dbname=$dbname; charset=utf8", $username, $password_db);

            // Préparer une requête SQL pour rechercher l'utilisateur par e-mail
            $sql = "SELECT id, token FROM utilisateurs WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC); 

            if($row){
                $user_id = $row['id']; 
                $token = hash('sha256', $user_id . time()); 
            }

            $sql = "UPDATE utilisateurs SET token = :token WHERE id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':user_id', $user_id); 
            $stmt->execute(); 

        } catch (PDOException $e){
            echo "Erreur dans l'inscription des données : " . $e->getMessage(); 
        }

        $conn = null;

        $subject = "Créer votre nouveau mot de passe"; 
        $message = "Veuillez cliquer sur ce lien pour créer votre nouveau mot de passe : http://php-dev-1.online/reset-password.php?token=" . urlencode($token); 

        $headers = "From: service@connexion.com\r\n";
        $headers .= "Reply-To: service@connexion.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Utilisez l'adresse IP et le port de MailHog comme serveur SMTP
    

        mail($email, $subject, $message, $headers); 

    }
}

$csfr_token = bin2hex(random_bytes(32)); 
$_SESSION['csrf_token'] = $csrf_token; 

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Connexion <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
  <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
  <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
</svg></a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">            
            <li class="nav-item">
                <a class="nav-link" href="signup.php">S'inscrire</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Mot de passe oublié</h2>
    <p>Entrez l’adresse e-mail associée à votre compte</p>
    <?php 
    if($email !== ""){
        echo " <div class='alert alert-success' role='alert' style='margin-bottom: -40px;'>
                Un mail à été envoyé à l'adresse $email ✓ 
               </div>";
    }
?>
    <form action="" method="POST" style='margin-top: 50px;'>
        <!-- Champ : Adresse e-mail -->
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

      
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Bouton d'envoi -->
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>