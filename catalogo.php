<?php
session_start();
include 'conexao.php';
$usuario_logado = isset($_SESSION['usuario_id']);

$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 12;
$offset = ($pagina - 1) * $limite;

$where = "WHERE 1=1";
if (!empty($busca)) {
  $where .= " AND (titulo LIKE '%$busca%' OR autor LIKE '%$busca%')";
}
if (!empty($genero)) {
  $where .= " AND genero = '$genero'";
}

$total_result = $conn->query("SELECT COUNT(*) as total FROM livros $where")->fetch_assoc()['total'];
$total_paginas = ceil($total_result / $limite);

$sql = "SELECT * FROM livros $where LIMIT $limite OFFSET $offset";
$result = $conn->query($sql);
$generos = $conn->query("SELECT DISTINCT genero FROM livros");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Livros</title>
  <link rel="stylesheet" href="css/layout.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      display: flex;
      flex-direction: column;
    }
    .content {
      flex: 1;
    }
    .top-bar {
      background-color: #ffffff;
      color: #2c3e50;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
    }
    .top-bar img.logo {
      height: 70px;
    }
    .top-bar .login-area a {
      color: #2c3e50;
      margin-left: 10px;
      text-decoration: none;
      background: #e0e0e0;
      padding: 6px 12px;
      border-radius: 4px;
    }
    .banner {
      background: url('img/banner.png') center center no-repeat;
      background-size: cover;
      height: 250px;
    }
    .filtros {
      padding: 30px 20px;
      background: white;
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      border-bottom: 1px solid #ddd;
    }
    .filtros input[type="text"] {
      flex: 1 1 300px;
      padding: 12px;
      font-size: 1em;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .filtros select, .filtros button {
      padding: 12px;
      font-size: 1em;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .livro {
      background: white;
      padding: 20px;
      margin: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      width: 280px;
      transition: transform 0.3s ease;
    }
    .livro:hover {
      transform: translateY(-5px);
    }
    .livro h2 {
      margin-top: 0;
      font-size: 1.2em;
      color: #2c3e50;
    }
    .catalogo {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 20px;
    }
    .paginacao {
      text-align: center;
      padding: 20px;
    }
    .paginacao a {
      display: inline-block;
      margin: 0 5px;
      padding: 8px 12px;
      background: #2c3e50;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
    .paginacao a.ativo {
      background: #27ae60;
    }
    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="content">
    <div class="top-bar">
      <a href="index.php"><img src="img/logo.png" alt="Logo" class="logo"></a>
      <div class="login-area">
        <?php if ($usuario_logado): ?>
          <a href="logout.php">Sair</a>
          <a href="meus_emprestimos.php">Meus Empréstimos</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a href="cadastro.php">Cadastrar</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="banner"></div>

    <form class="filtros" method="get" action="catalogo.php">
      <input type="text" name="busca" placeholder="Buscar por título ou autor" value="<?php echo htmlspecialchars($busca); ?>">
      <select name="genero">
        <option value="">Todos os Gêneros</option>
        <?php while ($row = $generos->fetch_assoc()): ?>
          <option value="<?php echo $row['genero']; ?>" <?php echo ($row['genero'] == $genero) ? 'selected' : ''; ?>><?php echo $row['genero']; ?></option>
        <?php endwhile; ?>
      </select>
      <button type="submit">Filtrar</button>
    </form>

    <div class="catalogo">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="livro">
          <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
          <p><strong>Autor:</strong> <?php echo htmlspecialchars($row['autor']); ?></p>
          <p><strong>Gênero:</strong> <?php echo htmlspecialchars($row['genero']); ?></p>
          <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
          <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
          <?php if ($row['status'] === 'disponível') {
            if ($usuario_logado) {
              echo "<form action='solicitar_emprestimo.php' method='POST'>";
              echo "<input type='hidden' name='livro_id' value='{$row['id']}'>";
              echo "<button type='submit'>Solicitar Empréstimo</button>";
              echo "</form>";
            } else {
              echo "<a href='login.php' style='display:inline-block; margin-top:10px; padding:8px 15px; background-color:#2980b9; color:white; text-decoration:none; border-radius:5px;'>Faça login para solicitar este livro</a>";
            }
          } ?>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="paginacao">
      <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>&busca=<?php echo urlencode($busca); ?>&genero=<?php echo urlencode($genero); ?>" class="<?php echo ($i == $pagina) ? 'ativo' : ''; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
  </div>

  <footer class="catalogo_footer">
    <p>&copy; <?php echo date('Y'); ?> Biblioteca Digital. Projeto Integrador UNIVESP.</p>
  </footer>
</body>
</html>
