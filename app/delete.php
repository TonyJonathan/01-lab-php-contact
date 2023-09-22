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
        $sql = "DELETE FROM contacts WHERE id = :id_contact";
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':id_contact', $id_contact); 
        $stmt->execute(); 
    } catch(PDOException $e){
        echo "Erreur lors de la suppression : " . $e->getMessage(); 
    }

    header('Location: dashbord.php'); 
    exit();
} 
?>