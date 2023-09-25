<?php
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Vérifiez si l'adresse e-mail de l'utilisateur est stockée dans la session
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(
        isset($_POST['csrf_token']) &&
        isset($_SESSION['csrf_token']) && 
        $_POST['csrf_token'] === $_SESSION['csrf_token']
    ) {
        $nom = $_POST['nom']; 
        $prenom = $_POST['prenom'];
        $email = $_POST['email']; 

        // Démarrez la session (assurez-vous de l'appeler avant d'utiliser $_SESSION)

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

                header('Location: dashbord.php'); 
                exit();
            } catch (PDOExeption $e) {
                echo "Erreur dans l'inscription des données : " . $e->getMessage(); 
            }

            //Fermer la connection à la base de données 
            $conn = null;
    } else {
        echo "CSRF error";
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
    <title>Tableau de Bord - Gestion des Contacts</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
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
                <a class="nav-link" href="deconnexion.php">Se déconnecter</a>
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
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" class="btn btn-primary">Ajouter Contact</button>
            </form>
        </div>
        <div class="col-md-6">
            <!-- Liste des contacts -->
            <h3>Liste des Contacts</h3>
            <ul class="list-group">
            <?php
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
        
                $sql = "SELECT id, nom, prenom, email FROM contacts WHERE utilisateur_id = :utilisateur_id"; 
                $stmt = $conn->prepare($sql); 
                $stmt->bindParam(':utilisateur_id', $user_id); 
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                foreach ($results as $row){
                    $nom_contact = $row['nom'];
                    $nom_contact = ucfirst($nom_contact);
                    $prenom_contact = $row['prenom']; 
                    $prenom_contact = ucfirst($prenom_contact);
                    $email_contact = $row['email'];
                    $id_contact = $row['id'];

                    echo "<li class='list-group-item' style='display: flex; align-items: center; justify-content: space-between;'>
                    <span>$prenom_contact $nom_contact - $email_contact</span>
                    <div>

                    <a href='delete.php?id=$id_contact'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16' style='float: right;'>
                        <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.920L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z'/>
                    </svg>
                    </a>

                    <a href='modifier.php?id=$id_contact'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16' style='float: right; margin-right: 7px; margin-top: 1px;'><path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                    <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/></svg>
                    </a>

                    
                    </div>
                  </li>";
            
                }
            } catch (PDOExeption $e) {
                echo "Erreur dans l'inscription des données : " . $e->getMessage(); 
            }
            ?>
            </ul>
        </div>
    </div>
</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

