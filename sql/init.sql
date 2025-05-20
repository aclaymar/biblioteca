<!-- Conteúdo simulado para sql/init.sql -->
CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  cidade VARCHAR(100),
  estado VARCHAR(100)
);

CREATE TABLE livros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  isbn VARCHAR(20),
  titulo VARCHAR(255) NOT NULL,
  autor VARCHAR(255) NOT NULL,
  editora VARCHAR(100),
  ano INT,
  idioma VARCHAR(50),
  paginas INT,
  status ENUM('disponivel', 'emprestado') DEFAULT 'disponivel'
);

CREATE TABLE emprestimos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT,
  id_livro INT,
  data_emprestimo DATE NOT NULL,
  data_devolucao DATE DEFAULT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
  FOREIGN KEY (id_livro) REFERENCES livros(id)
);
