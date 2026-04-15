<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "sistema");

// Verificação de conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo usuário registrado com sucesso";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Usuário</h2>
        <form action="register.php" method="post">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required><br>
            <input type="submit" value="Registrar">
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
    </div>
</body>
</html>

