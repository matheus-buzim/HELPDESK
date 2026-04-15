<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sistema");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chamado_id = $_POST['chamado_id'];
    $descricao = $_POST['descricao'];
    $data_andamento = date('Y-m-d H:i:s');

    $sql = "INSERT INTO andamentos (chamado_id, descricao, data_andamento) VALUES ('$chamado_id', '$descricao', '$data_andamento')";
    if ($conn->query($sql) === TRUE) {
        // Enviar email de notificação
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.exemplo.com';  // Defina o servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'usuario@exemplo.com'; // Email de envio
        $mail->Password = 'senha'; // Senha do email
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Consulta para obter o email do usuário que abriu o chamado
        $query_usuario = "SELECT u.email FROM usuarios u JOIN chamados c ON u.id = c.usuario_id WHERE c.id='$chamado_id'";
        $result_usuario = $conn->query($query_usuario);
        $usuario = $result_usuario->fetch_assoc();
        
        $mail->setFrom('suporte@exemplo.com', 'Suporte');
        $mail->addAddress($usuario['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Andamento do Chamado #' . $chamado_id;
        $mail->Body    = 'Houve um novo andamento no seu chamado: <br><br>' . $descricao;

        if (!$mail->send()) {
            echo 'Erro ao enviar email: ' . $mail->ErrorInfo;
        } else {
            echo 'Andamento inserido com sucesso e notificação enviada por email!';
        }
    } else {
        echo 'Erro ao inserir andamento: ' . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inserir Andamento</title>
</head>
<body>
    <h2>Inserir Andamento no Chamado</h2>
    <form action="inserir_andamento.php" method="post">
        <label for="chamado_id">ID do Chamado:</label><br>
        <input type="text" id="chamado_id" name="chamado_id" required><br>
        <label for="descricao">Descrição:</label><br>
        <textarea id="descricao" name="descricao" required></textarea><br>
        <input type="submit" value="Inserir Andamento">
    </form>
</body>
</html>
