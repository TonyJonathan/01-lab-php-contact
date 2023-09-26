<?php
setcookie('user_email', '', time() - 3600, '/');
setcookie('user_password', '', time() - 3600, '/'); 

header('Location: index.php'); 
exit(); 
?>