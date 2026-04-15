<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../backend/login.php");
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
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo ao Sistema de Chamados</h2>
        <p><a href="../backend/abrir_chamado.php">Abrir Novo Chamado</a></p>
        <h3>Meus Chamados</h3>
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
                    <a href="../backend/detalhes_chamado.php?id=<?php echo $row['id']; ?>">Ver Detalhes</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <p><a href="../backend/logout.php">Logout</a></p>
    </div>
</body>
</html>

<?php $conn->close(); ?>
