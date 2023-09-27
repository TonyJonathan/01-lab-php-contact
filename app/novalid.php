<?php
if($_SERVER['REQUEST_METHOD'] === "POST"){
    header('Location: oublie.php'); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Lien expiré</title>
</head>
<body>
    <p style='text-align: center; font-size: 160px;  margin-top: 100px;'>🚫</p>
        <h1 style='text-align: center;  margin-bottom: 20px;'> Votre URL à expiré, veuillez demander un autre email.<h1> 
        <form action="" method="POST" style="text-align: center;">
            <!-- Bouton d'envoi -->
            <button type="submit" class="btn btn-primary">Demander un autre email</button>
    </form>

</body>
</html>