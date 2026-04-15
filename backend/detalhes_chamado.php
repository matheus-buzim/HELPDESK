<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema");

if (isset($_GET['id'])) {
    $chamado_id = $_GET['id'];
    $query_chamado = "SELECT * FROM chamados WHERE id='$chamado_id'";
    $result_chamado = $conn->query($query_chamado);
    $chamado = $result_chamado->fetch_assoc();

    $query_andamentos = "SELECT * FROM andamentos WHERE chamado_id='$chamado_id' ORDER BY data_andamento ASC";
    $result_andamentos = $conn->query($query_andamentos);
} else {
    echo "Chamado não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Chamado</title>
</head>
<body>
    <h2>Detalhes do Chamado</h2>
    <p><strong>Assunto:</strong> <?php echo $chamado['assunto']; ?></p>
    <p><strong>Status:</strong> <?php echo $chamado['status']; ?></p>
    <p><strong>Data de Criação:</strong> <?php echo $chamado['data_criacao']; ?></p>

    <h3>Histórico de Andamentos</h3>
    <table border="1">
        <tr>
            <th>Data</th>
            <th>Descrição</th>
        </tr>
        <?php while($andamento = $result_andamentos->fetch_assoc()): ?>
        <tr>
            <td><?php echo $andamento['data_andamento']; ?></td>
            <td><?php echo $andamento['descricao']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
