<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Logout</title>
        <link rel="stylesheet" type="text/css" href="../assets/styles.css">
    </head>

    <body>
        <div class="container">
            <h2>Você saiu da sua conta</h2>
            <p>Você foi desconectado com sucesso.</p>
            <p><a href="login.php">Clique aqui para fazer login novamente</a></p>
        </div>
    </body>
    
</html>
