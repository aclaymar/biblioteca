<?php
include 'auth_middleware.php';
include 'conexao.php';

// Verifica se é admin - exemplo simples
$usuario_id = $_SESSION['usuario_id'];
$verifica = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
$verifica->bind_param("i", $usuario_id);
$verifica->execute();
$admin = $verifica->get_result()->fetch_assoc();

$is_admin = isset($admin['email']) && $admin['email'] === 'admin@biblioteca.com';

if (!$is_admin) {
  echo "<p style='color:red;'>Acesso restrito ao administrador.</p>";
  exit();
}

// Coleta dados para o dashboard
$total_livros = $conn->query("SELECT COUNT(*) AS total FROM livros")->fetch_assoc()['total'];
$total_usuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$total_emprestimos = $conn->query("SELECT COUNT(*) AS total FROM emprestimos WHERE status = 'ativo'")->fetch_assoc()['total'];

$sql = "SELECT e.id, l.titulo, u.nome, u.email, e.data_emprestimo, e.data_prevista, e.data_devolucao, e.status
        FROM emprestimos e
        JOIN livros l ON e.livro_id = l.id
        JOIN usuarios u ON e.usuario_id = u.id
        ORDER BY e.data_emprestimo DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel Admin</title>
  <link rel="stylesheet" href="css/layout.css">
  <style>
    body { font-family: 'Segoe UI', sans-serif; padding: 20px; background: #f5f5f5; }
    h1 { color: #2c3e50; }
    .dashboard { margin-bottom: 30px; display: flex; gap: 30px; }
    .card {
      background: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      flex: 1;
    }
    .card h2 { margin: 0 0 10px; font-size: 1.2em; color: #555; }
    .card p { font-size: 2em; margin: 0; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #eee; }
    .atrasado { background-color: #ffe5e5; }
  </style>
</head>
<body>
  <h1>Painel Administrativo - Empréstimos</h1>

  <div class="dashboard">
    <div class="card">
      <h2>Total de Livros</h2>
      <p><?php echo $total_livros; ?></p>
    </div>
    <div class="card">
      <h2>Total de Usuários</h2>
      <p><?php echo $total_usuarios; ?></p>
    </div>
    <div class="card">
      <h2>Empréstimos Ativos</h2>
      <p><?php echo $total_emprestimos; ?></p>
    </div>
  </div>

  <table>
    <tr>
      <th>Livro</th>
      <th>Usuário</th>
      <th>Email</th>
      <th>Emprestado em</th>
      <th>Previsto para</th>
      <th>Devolvido em</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
        $hoje = date('Y-m-d');
        $classe = ($row['status'] === 'ativo' && $row['data_prevista'] < $hoje) ? 'atrasado' : '';
      ?>
      <tr class="<?php echo $classe; ?>">
        <td><?php echo htmlspecialchars($row['titulo']); ?></td>
        <td><?php echo htmlspecialchars($row['nome']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo $row['data_emprestimo']; ?></td>
        <td><?php echo $row['data_prevista']; ?></td>
        <td><?php echo $row['data_devolucao'] ?: '---'; ?></td>
        <td><?php echo ucfirst($row['status']); ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
