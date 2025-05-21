<?php
session_start();
include 'conexao.php';

if (isset($_POST['livro_id']) && isset($_SESSION['usuario_id'])) {
    $livro_id = (int) $_POST['livro_id'];
    $usuario_id = (int) $_SESSION['usuario_id'];

    // Verificar se o livro está disponível
    $stmt = $conn->prepare("SELECT status FROM livros WHERE id = ?");
    $stmt->bind_param("i", $livro_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['status'] === 'disponível') {
        // Atualiza o status do livro para "emprestado"
        $update = $conn->prepare("UPDATE livros SET status = 'emprestado' WHERE id = ?");
        $update->bind_param("i", $livro_id);
        $update->execute();

        // Inserir com data prevista de devolução em 7 dias
        $inserir = $conn->prepare("INSERT INTO emprestimos (usuario_id, livro_id, data_emprestimo, data_prevista, status) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'ativo')");
        $inserir->bind_param("ii", $usuario_id, $livro_id);
        $inserir->execute();

        echo "<script>alert('Empréstimo realizado com sucesso!'); window.location.href = 'meus_emprestimos.php';</script>";
    } else {
        echo "<script>alert('Este livro não está disponível para empréstimo.'); window.location.href = 'catalogo.php';</script>";
    }
} else {
    echo "<script>alert('Você precisa estar logado para solicitar um empréstimo.'); window.location.href = 'login.php';</script>";
}
?>
