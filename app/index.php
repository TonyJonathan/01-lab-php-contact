<?php
session_start();
if (isset($_COOKIE['user_email']) && isset ($_COOKIE['user_password'])){
    $email = $_COOKIE['user_email']; 
    $password = $_COOKIE['user_password']; 

    try{
        // Établir une connexion à la base de données avec PDO
        $servername = "mysql:host=mysql";
        $username = getenv("MYSQL_USER");
        $password_db = getenv("MYSQL_PASSWORD");
        $dbname = getenv("MYSQL_DATABASE");
        

        $conn = new PDO("$servername;dbname=$dbname; charset=utf8", $username, $password_db);

        // Préparer une requête SQL pour rechercher l'utilisateur par e-mail
        $sql = "SELECT id, mot_de_passe, sel FROM utilisateurs WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Récupérer le résultat de la requête
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si un utilisateur correspondant est trouvé

        if ($row) {
            $hashed_password_data = $row['mot_de_passe'];
            $sel = $row['sel'];
        
            // Utilisez password_verify pour vérifier le mot de passe
            if (password_verify($password . $sel, $hashed_password_data)) {
                // Retrouver le nom de l'id
                $user_id = $row['id'];
        
                $_SESSION['user_id'] = $user_id;
        
                // Authentification réussie, rediriger l'utilisateur vers la page d'accueil ou autre page sécurisée
                header('Location: dashbord.php');
                exit();
            } else {
                $login_error = "error";
            }
        } else {
            $login_error = "error";
        }
        
    } catch (PDOException $e){
        echo "Erreur de base de données : " . $e->getMessage(); 
    } 

    $conn = null; 
} else {


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $login_error = "";
    // Vérification du formulaire de connexion
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(
            isset($_SESSION['csrf_token']) &&
            isset($_POST['csrf_token']) && 
            $_SESSION['csrf_token'] === $_POST['csrf_token']
        ) {

            $email = $_POST["email"];
            $password = $_POST["password"];
            $rememberMe = isset($_POST['rememberMe']); 
            if($rememberMe){
                // Créer un cookie avec l'email et le mot de passe 
                setcookie('user_email', $email, time() + 3600 * 24 * 2, '/');
                setcookie('user_password', $password, time() + 3600 * 24 * 2, '/'); 
            }

            try{
                // Établir une connexion à la base de données avec PDO
                $servername = "mysql:host=mysql";
                $username = getenv("MYSQL_USER");
                $password_db = getenv("MYSQL_PASSWORD");
                $dbname = getenv("MYSQL_DATABASE");
                

                $conn = new PDO("$servername;dbname=$dbname; charset=utf8", $username, $password_db);

                // Préparer une requête SQL pour rechercher l'utilisateur par e-mail
                $sql = "SELECT id, mot_de_passe, sel FROM utilisateurs WHERE email = :email";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Récupérer le résultat de la requête
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Si un utilisateur correspondant est trouvé

                if ($row) {
                    $hashed_password_data = $row['mot_de_passe'];
                    $sel = $row['sel'];
                
                    // Utilisez password_verify pour vérifier le mot de passe
                    if (password_verify($password . $sel, $hashed_password_data)) {
                        // Retrouver le nom de l'id
                        $user_id = $row['id'];
                
                        $_SESSION['user_id'] = $user_id;
                
                        // Authentification réussie, rediriger l'utilisateur vers la page d'accueil ou autre page sécurisée
                        header('Location: dashbord.php');
                        exit();
                    } else {
                        $login_error = "error";
                    }
                } else {
                    $login_error = "error";
                }
                
            } catch (PDOException $e){
                echo "Erreur de base de données : " . $e->getMessage(); 
            } 

            $conn = null; 

        } else {
            $login_error = "CSRF error"; 
        }

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
    <title>Formulaire de Connexion</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Mon Site</a>
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
    <h2>Formulaire de Connexion</h2>

    <?php 
            if($login_error === "error"){
                echo "<div class='alert alert-danger' role='alert'>
                Les identifiants sont incorrectes.
              </div>"; 
            }
        ?>
    <form action="" method="POST">
        <!-- Champ : Adresse e-mail -->
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Champ : Mot de passe -->
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Option : Se souvenir de moi -->
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
            <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
        </div>

        <!-- Lien : Mot de passe oublié -->
        <div class="form-group">
            <a href="#">Mot de passe oublié ?</a>
        </div>

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Bouton d'envoi -->
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
