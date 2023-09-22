
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

        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Modifier</h2>
    <p>Vous pouvez mettre à jour les informations de vos contacts ici.</p>
    <div class="row">
        <div class="col-md-6">
            <!-- Formulaire d'ajout de contact -->
            <form action="" method="POST">
                <?php

if (isset($_GET['id'])){
    $id_contact = $_GET['id']; 
        // Établir une connexion à la base de données avec PDO
        $servername = "mysql:host=mysql";
        $username = getenv("MYSQL_USER");
        $password_db = getenv("MYSQL_PASSWORD");
        $dbname = getenv("MYSQL_DATABASE");

        $conn = new PDO("$servername;dbname=$dbname;charset=utf8", $username, $password_db);
        
        // Définir le mode d'erreur PDO sur exception (sert à gérer les erreurs plus facilement)
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try{
        $sql = "SELECT nom, prenom, email, utilisateur_id FROM contacts WHERE id = :id_contact";
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':id_contact', $id_contact); 
        $stmt->execute(); 
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 

            foreach ($results as $row){
            $nom_contact = $row['nom'];
            $prenom_contact = $row['prenom']; 
            $email_contact = $row['email'];
            $id_utilisateur = $row['utilisateur_id'];

            echo "<div class='form-group'>
                <label for='nom'>Nom</label>
                <input type='text' class='form-control' id='nom' name='nom' value='$nom_contact' required>
            </div>
            <div class='form-group'>
                <label for='prenom'>Prénom</label>
                <input type='text' class='form-control' id='prenom' name='prenom' value='$prenom_contact' required>
            </div>
            <div class='form-group'>
                <label for='email'>Adresse e-mail</label>
                <input type='email' class='form-control' id='email' name='email' value='$email_contact' required>
            </div>
            
            <div class='form-group'>
                <label for='prenom'>id_utilisateur</label>
                <input type='text' class='form-control' id='id_utilisateur' name='id_utilisateur' value='$id_utilisateur' required>
            </div>";
            }


    } catch(PDOException $e){
        echo "Erreur lors de la suppression : " . $e->getMessage(); 
    }

} 







                

                ?>

                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </div>
        <div class="col-md-6">

</body>
</html>