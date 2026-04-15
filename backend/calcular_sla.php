<?php
$conn = new mysqli("localhost", "root", "", "sistema");

function calcularSLA($chamado_id) {
    global $conn;
    $query = "SELECT tempo_estimado FROM slas WHERE chamado_id='$chamado_id'";
    $result = $conn->query($query);
    $sla = $result->fetch_assoc();
    
    $query_andamentos = "SELECT data_andamento FROM andamentos WHERE chamado_id='$chamado_id' ORDER BY data_andamento ASC";
    $result_andamentos = $conn->query($query_andamentos);
    
    $data_criacao = null;
    $tempo_total = 0;
    
    while ($andamento = $result_andamentos->fetch_assoc()) {
        if ($data_criacao == null) {
            $data_criacao = strtotime($andamento['data_andamento']);
        } else {
            $data_andamento = strtotime($andamento['data_andamento']);
            $tempo_total += ($data_andamento - $data_criacao) / 3600; // Converte de segundos para horas
            $data_criacao = $data_andamento;
        }
    }
    
    $tempo_real = $tempo_total;
    $query_update = "UPDATE slas SET tempo_real='$tempo_real' WHERE chamado_id='$chamado_id'";
    $conn->query($query_update);
    
    return $tempo_real <= $sla['tempo_estimado'];
}

// Exemplo de uso
$chamado_id = 1; // ID do chamado para teste
if (calcularSLA($chamado_id)) {
    echo "SLA cumprido!";
} else {
    echo "SLA não cumprido!";
}

$conn->close();
?>
