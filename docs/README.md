# 📚 Biblioteca Digital - Projeto Integrador UNIVESP
(Sumário reduzido — completo já enviado em conversa anterior)
# 📚 Biblioteca Digital — Projeto Integrador UNIVESP

Este projeto é uma aplicação web desenvolvida em PHP, MySQL e HTML/CSS com o objetivo de gerenciar empréstimos de livros, promovendo o acesso digital à leitura. Conta com painel de administração, login social e controle completo dos empréstimos.

---

## 🚀 Funcionalidades

### 🧑‍💻 Acesso e Autenticação
- Login com email e senha
- Integração com Google (via Firebase)
- Integração com Facebook
- Redirecionamento automático se o usuário já estiver logado

### 📖 Catálogo de Livros
- Lista todos os livros disponíveis
- Filtro por título, autor e gênero
- Paginação automática (12 por página)
- Layout visual com banner, cards estilizados e responsividade
- Acesso livre ao catálogo, mas somente usuários logados podem solicitar empréstimos

### 📥 Empréstimos e Devoluções
- Usuários logados podem solicitar livros disponíveis
- Visualização de "Meus Empréstimos"
- Registro de devoluções
- Atualização automática do status do livro para "disponível"
- Destaque de empréstimos devolvidos e ativos

### 🛠 Painel Administrativo
- Resumo de livros, usuários e empréstimos ativos
- Listagem de todos os empréstimos
- Marcação de atrasados com destaque visual
- Cadastro de novos livros diretamente pelo painel

---

## ⚙️ Tecnologias Utilizadas

- PHP 7+
- MySQL
- HTML5, CSS3
- Firebase (para login social com Google)
- Bootstrap (em partes do layout)
- Git e GitHub

---

## 📂 Organização de Pastas

biblioteca/
│
├── css/ # Estilos
├── img/ # Imagens do site
├── js/ # Scripts JavaScript (opcional)
├── conexao.php # Conexão com o banco de dados
├── index.php # Página inicial com banner e login
├── catalogo.php # Listagem dos livros com filtros
├── login.php # Tela de login
├── cadastro.php # Cadastro de novos usuários
├── solicitar_emprestimo.php
├── devolver_livro.php
├── meus_emprestimos.php
├── painel_admin.php # Administração de livros e empréstimos
└── README.md
