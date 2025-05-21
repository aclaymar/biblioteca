<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit();
}

if (isset($_POST['emprestimo_id']) && is_numeric($_POST['emprestimo_id'])) {
  $emprestimo_id = $_POST['emprestimo_id'];

  // Atualizar emprestimo como devolvido
  $sql = "UPDATE emprestimos 
          SET status = 'devolvido', data_devolucao = CURDATE() 
          WHERE id = ? AND usuario_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $emprestimo_id, $_SESSION['usuario_id']);
  $stmt->execute();

  // Buscar o livro relacionado
  $sql_livro = "SELECT livro_id FROM emprestimos WHERE id = ?";
  $stmt2 = $conn->prepare($sql_livro);
  $stmt2->bind_param("i", $emprestimo_id);
  $stmt2->execute();
  $result = $stmt2->get_result();
  $livro = $result->fetch_assoc();

  // Atualizar status do livro para disponível
  if ($livro) {
    $livro_id = $livro['livro_id'];
    $conn->query("UPDATE livros SET status = 'disponível' WHERE id = $livro_id");
  }
}

header("Location: meus_emprestimos.php");
exit();
