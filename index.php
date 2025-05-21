<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Biblioteca Digital - UNIVESP">
  <link rel="icon" href="img/logo.png" type="image/png">
  <title>Biblioteca Digital</title>
  <link rel="stylesheet" href="css/layout.css">
<link rel="stylesheet" href="css/buttons.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/typography.css">
<link rel="stylesheet" href="css/responsive.css">



</head>
<body>
  <div class="hero">
    <div class="top-bar">
      <img src="img/logo.png" alt="Logotipo da Biblioteca" class="logo">
      <div class="login-area">
      <div style="display: flex; justify-content: center; gap: 10px; top: 010px; right: 50px; position: absolute;">
  <a href="login.php" style="padding: 10px 20px; background: #2c3e50; color: white; text-decoration: none; border-radius: 5px;">Entrar</a>
  <a href="cadastro.php" style="padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px;">Cadastrar</a>
</div>

      </div>
    </div>

    
    <a class="catalogo-button" href="catalogo.php">Catálogo</a>
    <footer>
      <p>&copy; 2025 Biblioteca Digital. Projeto Integrador UNIVESP.</p>
    </footer>
  </div>

  <!-- Firebase SDK -->
  <script src="https://www.gstatic.com/firebasejs/10.3.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.3.0/firebase-auth.js"></script>
  <script>
    const firebaseConfig = {
      apiKey: "SUA_API_KEY",
      authDomain: "SEU_PROJETO.firebaseapp.com",
      projectId: "SEU_PROJETO_ID",
      appId: "SUA_APP_ID"
    };


    firebase.initializeApp(firebaseConfig);
    const provider = new firebase.auth.GoogleAuthProvider();

    function loginComGoogle() {
      firebase.auth()
        .signInWithPopup(provider)
        .then((result) => {
          const usuario = result.user;
          alert("Bem-vindo, " + usuario.displayName);
          window.location.href = "dashboard.php";
        })
        .catch((error) => {
          console.error("Erro ao logar com Google:", error);
          alert("Erro ao logar com Google: " + error.message);
        });
    }
  </script>
</body>
</html>
