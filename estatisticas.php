header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=relatorio.csv');
// gerar CSV com fputcsv()
<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=relatorio_emprestimos.csv');

include 'conexao.php';

$output = fopen('php://output', 'w');

// Cabeçalhos da tabela
fputcsv($output, ['Usuário', 'Livro', 'Data de Empréstimo', 'Data de Devolução', 'Status']);

// Consulta
$sql = "SELECT u.nome AS usuario, l.titulo AS livro, e.data_emprestimo, e.data_devolucao, e.status
        FROM emprestimos e
        JOIN usuarios u ON e.usuario_id = u.id
        JOIN livros l ON e.livro_id = l.id
        ORDER BY e.data_emprestimo DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  fputcsv($output, [$row['usuario'], $row['livro'], $row['data_emprestimo'], $row['data_devolucao'], $row['status']]);
}

fclose($output);
exit();
