<!-- Conteúdo simulado para admin.php -->
CREATE TABLE emprestimos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  livro_id INT,
  data_emprestimo DATE,
  data_devolucao DATE,
  status ENUM('ativo','devolvido') DEFAULT 'ativo'
);

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administração de Empréstimos</title>
  <link rel="stylesheet" href="css/layout.css">
  <style>
    body {
      background-color: #fff;
      font-family: 'Segoe UI', sans-serif;
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #2c3e50;
      color: white;
    }
    .btn-devolver {
      padding: 6px 12px;
      background-color: #27ae60;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-devolver:hover {
      background-color: #219150;
    }
  </style>
</head>
<body>
  <h1>Painel Administrativo - Empréstimos</h1>
  <a href="estatisticas.php" target="_blank" style="float: right; margin-top: 10px;">📥 Exportar CSV</a>

  <table>
    <tr>
      <th>Usuário</th>
      <th>Livro</th>
      <th>Data de Empréstimo</th>
      <th>Data de Devolução</th>
      <th>Status</th>
      <th>Ação</th>
    </tr>
    <?php
    include 'conexao.php';
    $sql = "SELECT e.id, u.nome AS usuario, l.titulo AS livro, e.data_emprestimo, e.data_devolucao, e.status
            FROM emprestimos e
            JOIN usuarios u ON e.usuario_id = u.id
            JOIN livros l ON e.livro_id = l.id
            ORDER BY e.data_emprestimo DESC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>{$row['usuario']}</td>";
      echo "<td>{$row['livro']}</td>";
      echo "<td>{$row['data_emprestimo']}</td>";
      echo "<td>{$row['data_devolucao']}</td>";
      echo "<td>{$row['status']}</td>";
      echo "<td>";
      if ($row['status'] == 'ativo') {
        echo "<form action='devolver.php' method='POST'>";
        echo "<input type='hidden' name='id' value='{$row['id']}'>";
        echo "<button class='btn-devolver' type='submit'>Marcar como devolvido</button>";
        echo "</form>";
      } else {
        echo "-";
      }
      echo "</td>";
      echo "</tr>";
    }
    ?>
  </table>
</body>
</html>
