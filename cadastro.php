<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Verificar se o e-mail já existe
  $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    $erro = "Este e-mail já está cadastrado.";
  } else {
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $hash);
    if ($stmt->execute()) {
      header("Location: login.php?cadastro=ok");
      exit();
    } else {
      $erro = "Erro ao cadastrar usuário.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastro</title>
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
      background-color: #27ae60;
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
    <h2>Criar Conta</h2>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="post">
      <input type="text" name="nome" placeholder="Nome completo" required>
      <input type="email" name="email" placeholder="Seu e-mail" required>
      <input type="password" name="senha" placeholder="Crie uma senha" required>
      <button type="submit">Cadastrar</button>
    </form>
    <p style="text-align: center; margin-top: 10px;">
      Já tem conta? <a href="login.php">Entrar</a>
    </p>
  </div>
</body>
</html>
