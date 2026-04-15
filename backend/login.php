<?php
session_start();
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "sistema");

// Verificação de conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    md5($senha);

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['usuario_id'] = $row['id'];
            header("Location: \index.php");
        } else {
            echo "Senha incorreta";
        }
    } else {
        echo "Usuário não encontrado";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required><br>
            <input type="submit" value="Login">
        </form>
        <p>Não tem uma conta? <a href="register.php">Registre-se aqui</a></p>
    </div>
</body>
</html>


