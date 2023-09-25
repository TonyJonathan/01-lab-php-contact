<?php
setcookie('user_email', '', time() - 3600, '/');
setcookie('user_password', '', time() - 3600, '/'); 

session_destroy();
header('Location: index.php'); 
exit(); 
?>