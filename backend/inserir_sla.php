<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chamado_id = $_POST['chamado_id'];
    $tempo_estimado = $_POST['tempo_estimado'];

    $sql = "INSERT INTO slas (chamado_id, tempo_estimado) VALUES ('$chamado_id', '$tempo_estimado')";
    if ($conn->query($sql) === TRUE) {
        echo 'SLA inserido com sucesso!';
    } else {
        echo 'Erro ao inserir SLA: ' . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inserir SLA</title>
</head>
<body>
    <h2>Inserir SLA no Chamado</h2>
    <form action="inserir_sla.php" method="post">
        <label for="chamado_id">ID do Chamado:</label><br>
        <input type="text" id="chamado_id" name="chamado_id" required><br>
        <label for="tempo_estimado">Tempo Estimado (em horas):</label><br>
        <input type="number" id="tempo_estimado" name="tempo_estimado" required><br>
        <input type="submit" value="Inserir SLA">
    </form>
</body>
</html>
