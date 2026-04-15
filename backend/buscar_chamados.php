<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema");
$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filtro = $_POST['filtro'];
    $query = "SELECT * FROM chamados WHERE usuario_id='$usuario_id' AND (assunto LIKE '%$filtro%' OR status LIKE '%$filtro%' OR data_criacao LIKE '%$filtro%')";
    $result = $conn->query($query);
} else {
    $result = $conn->query("SELECT * FROM chamados WHERE usuario_id='$usuario_id'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Chamados</title>
</head>
<body>
    <h2>Buscar Chamados</h2>
    <form action="buscar_chamados.php" method="post">
        <label for="filtro">Filtro:</label>
        <input type="text" id="filtro" name="filtro">
        <input type="submit" value="Buscar">
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Assunto</th>
            <th>Status</th>
            <th>Data de Criação</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['assunto']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['data_criacao']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
