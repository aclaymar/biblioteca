<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Processar devolução se enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emprestimo_id'])) {
  $emprestimo_id = (int) $_POST['emprestimo_id'];
  $update = $conn->prepare("UPDATE emprestimos SET status = 'devolvido', data_devolucao = NOW() WHERE id = ? AND usuario_id = ?");
  $update->bind_param("ii", $emprestimo_id, $usuario_id);
  $update->execute();
}

$sql = "SELECT e.id, l.titulo, e.data_emprestimo, e.data_prevista, e.data_devolucao, e.status
        FROM emprestimos e
        JOIN livros l ON e.livro_id = l.id
        WHERE e.usuario_id = ?
        ORDER BY e.data_emprestimo DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Meus Empréstimos</title>
  <link rel="stylesheet" href="css/layout.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f0f0;
      padding: 20px;
    }
    .top-nav {
      margin-bottom: 20px;
    }
    .top-nav a {
      margin-right: 15px;
      text-decoration: none;
      color: #2c3e50;
    }
    .emprestimo {
      background: white;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    .status-ativo { color: green; font-weight: bold; }
    .status-devolvido { color: gray; font-weight: bold; }
    form.devolver {
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="top-nav">
    <a href="index.php">Início</a>
    <a href="catalogo.php">Catálogo</a>
    <a href="meus_emprestimos.php">Meus Empréstimos</a>
    <a href="logout.php">Sair</a>
  </div>

  <h1>Meus Empréstimos</h1>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="emprestimo">
      <p><strong>Livro:</strong> <?php echo htmlspecialchars($row['titulo']); ?></p>
      <p><strong>Data de Empréstimo:</strong> <?php echo $row['data_emprestimo']; ?></p>
      <p><strong>Data Prevista:</strong> <?php echo $row['data_prevista'] ?: '---'; ?></p>
      <p><strong>Status:</strong> <span class="status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></p>
      <?php if ($row['data_devolucao']): ?>
        <p><strong>Devolvido em:</strong> <?php echo $row['data_devolucao']; ?></p>
      <?php endif; ?>

      <?php if ($row['status'] === 'ativo'): ?>
        <form class="devolver" method="POST" action="devolver_livro.php">
  <input type="hidden" name="emprestimo_id" value="<?php echo $row['id']; ?>">
  <button type="submit">Registrar Devolução</button>
</form>


      <?php endif; ?>
    </div>
  <?php endwhile; ?>
</body>
</html>
