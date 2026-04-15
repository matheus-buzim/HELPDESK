<?php
require 'vendor/autoload.php';
use PhpImap\Mailbox;

$mailbox = new Mailbox('{imap.seu-servidor-email.com:993/imap/ssl}INBOX', 'seu-email@exemplo.com', 'sua-senha', __DIR__);
$conn = new mysqli("localhost", "root", "", "sistema_chamados");

try {
    $mailsIds = $mailbox->searchMailbox('UNSEEN');
    if (!$mailsIds) {
        die('Nenhum e-mail não lido encontrado');
    }

    foreach ($mailsIds as $mailId) {
        $email = $mailbox->getMail($mailId);
        $assunto = $conn->real_escape_string($email->subject);
        $mensagem = $conn->real_escape_string($email->textHtml);
        $usuario_id = 1; // Você pode usar lógica para associar o e-mail ao usuário correto
        $data_criacao = date('Y-m-d H:i:s');
        $status = 'ABERTO';

        // Verificar se já existe um chamado com o mesmo assunto
        $sql_verificar = "SELECT id FROM chamados WHERE assunto='$assunto' AND status != 'ENCERRADO'";
        $result_verificar = $conn->query($sql_verificar);
        
        if ($result_verificar->num_rows > 0) {
            // Se já existir, insira um andamento
            $chamado = $result_verificar->fetch_assoc();
            $chamado_id = $chamado['id'];
            $sql_andamento = "INSERT INTO andamentos (chamado_id, descricao, data_andamento) VALUES ('$chamado_id', '$mensagem', '$data_criacao')";
            $conn->query($sql_andamento);
        } else {
            // Se não existir, crie um novo chamado
            $sql = "INSERT INTO chamados (usuario_id, assunto, status, data_criacao) VALUES ('$usuario_id', '$assunto', '$status', '$data_criacao')";
            if ($conn->query($sql) === TRUE) {
                $chamado_id = $conn->insert_id;
                $sql_andamento = "INSERT INTO andamentos (chamado_id, descricao, data_andamento) VALUES ('$chamado_id', '$mensagem', '$data_criacao')";
                $conn->query($sql_andamento);
                echo "Chamado criado com sucesso a partir do e-mail!";
            } else {
                echo "Erro ao criar chamado: " . $conn->error;
            }
        }

        $mailbox->markMailAsRead($mailId);
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

$conn->close();
?>
