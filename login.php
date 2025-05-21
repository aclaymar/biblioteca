<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($senha, $user['senha'])) {
      $_SESSION['usuario_id'] = $user['id'];
      header("Location: catalogo.php");
      exit();
    } else {
      $erro = "Senha incorreta.";
    }
  } else {
    $erro = "Usuário não encontrado.";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/layout.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f1f1f1;
      padding: 30px;
    }
    .container {
      max-width: 400px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: #2c3e50;
      color: white;
      cursor: pointer;
    }
    .erro {
      color: red;
      font-size: 0.9em;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="post">
      <input type="email" name="email" placeholder="Seu email" required>
      <input type="password" name="senha" placeholder="Sua senha" required>
      <button type="submit">Entrar</button>
    </form>
    <p style="text-align: center; margin-top: 10px;">
  Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
</p>

  </div>
</body>
</html>

