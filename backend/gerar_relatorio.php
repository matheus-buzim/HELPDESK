<?php
require_once('C:\Users\mrvieira\vendor\tecnickcom\tcpdf\tcpdf.php');

$conn = new mysqli("localhost", "root", "", "sistema");

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, 'Relatório de SLA', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Chamados');
$pdf->SetTitle('Relatório de SLA');
$pdf->SetSubject('Relatório Detalhado');

$pdf->AddPage();

$pdf->SetFont('helvetica', '', 10);
$query = "SELECT * FROM slas";
$result = $conn->query($query);

$html = '<table border="1" cellpadding="4">
            <tr>
                <th>ID do Chamado</th>
                <th>Tempo Estimado (Horas)</th>
                <th>Tempo Real (Horas)</th>
                <th>Status SLA</th>
            </tr>';

while ($sla = $result->fetch_assoc()) {
    $status = ($sla['tempo_real'] <= $sla['tempo_estimado']) ? 'Cumprido' : 'Não Cumprido';
    $html .= '<tr>
                <td>' . $sla['chamado_id'] . '</td>
                <td>' . $sla['tempo_estimado'] . '</td>
                <td>' . $sla['tempo_real'] . '</td>
                <td>' . $status . '</td>
              </tr>';
}

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('relatorio_sla.pdf', 'I');

$conn->close();
?>
