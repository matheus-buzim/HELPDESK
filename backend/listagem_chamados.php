<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema");
$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM chamados WHERE usuario_id='$usuario_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listagem de Chamados</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <div class="container">
        <h2>Meus Chamados</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Assunto</th>
                <th>Status</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['assunto']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['data_criacao']; ?></td>
                <td>
                    <a href="detalhes_chamado.php?id=<?php echo $row['id']; ?>">Ver Detalhes</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="index.php">Voltar ao Dashboard</a></p>
    </div>
</body>
</html>

<?php $conn->close(); ?>
