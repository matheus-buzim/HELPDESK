<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $assunto = $_POST['assunto'];
    $descricao = $_POST['descricao'];
    $data_criacao = date('Y-m-d H:i:s');
    $status = 'ABERTO';

    $sql = "INSERT INTO chamados (usuario_id, assunto, status, data_criacao) VALUES ('$usuario_id', '$assunto', '$status', '$data_criacao')";

    if ($conn->query($sql) === TRUE) {
        $chamado_id = $conn->insert_id;
        $sql_andamento = "INSERT INTO andamentos (chamado_id, descricao, data_andamento) VALUES ('$chamado_id', '$descricao', '$data_criacao')";
        $conn->query($sql_andamento);
        echo "Chamado aberto com sucesso!";
    } else {
        echo "Erro ao abrir chamado: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Abrir Chamado</title>
</head>
<body>
    <h2>Abrir Novo Chamado</h2>
    <form action="abrir_chamado.php" method="post">
        <label for="assunto">Assunto:</label><br>
        <input type="text" id="assunto" name="assunto" required><br>
        <label for="descricao">Descrição:</label><br>
        <textarea id="descricao" name="descricao" required></textarea><br>
        <input type="submit" value="Abrir Chamado">
    </form>
</body>
</html>
