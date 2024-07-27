<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $servername = "localhost";
    $db_username = "root";
    $password_db = "";
    $dbname = "library_magicbook";

    $conn = new mysqli($servername, $db_username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("A conexão falhou: " . $conn->connect_error);
    }

    $sql = "SELECT user_id, user_type, username FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Erro na consulta SQL: " . $conn->error);
    }

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['user_type'] = $row['user_type'];

      if ($row['user_type'] === 'admin') {
          header("Location: admin_page.php");
          exit();
      } else {
          header("Location: user_page.php");
          exit();
      }
  }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <title>Página de Login - Livraria Magic Book</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #logolivraria{
            width:120 ;
            height:90;

        }
        </style>
  </head>
  <body class="p-3 m-0 border-0 bd-example m-0 border-0" style="width:100%; height: 70%;">
  <nav class="navbar bg-dark border-bottom border-body navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="navbar-brand">
            <a href="login.php">
                <img src="../Src/Images/logo.png" alt="logolivraria" id="logolivraria" width="90" height="60" class="img-fluid">
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="historia.php">Sobre Nós</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categorias
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="romances.php">Romance</a></li>
                        <li><a class="dropdown-item" href="biografia.php">Biografia</a></li>
                        <li><a class="dropdown-item" href="infantil.php">Infantil</a></li>
                        <li><a class="dropdown-item" href="humor.php">Humor</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex">
                <a href="product.php" class="nav-link">
                    <i class="fas fa-search" title="Todos os produtos" style="font-size: 25px; color: azure; margin-right: 15px;"></i>
                </a>
                <li class="nav-item dropdown" style="list-style: none;">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         <i class="fas fa-user" title="Meu perfil" style="font-size: 25px; color: azure;"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin' ? 'admin_page.php' : 'user_page.php'; ?>">Meu Perfil</a></li>
                        <?php
                        if (isset($_SESSION["user_id"])) {
                            echo '<li><a class="dropdown-item" href="logout.php">Logout</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <a href="carrinho.php" class="nav-link">
                    <i class="fas fa-shopping-cart" title="Meu carrinho" style="font-size: 25px;color: azure;margin-right: 15px; margin-left: 10px;"></i>
                </a>
                <a href="favorites.php" class="nav-link">
                    <i class="fas fa-heart" title="Meus favoritos" style="font-size: 25px;color: azure;margin-right: 15px;"></i>
                </a>
            </form>
        </div>
    </div>
</nav>
      <div class="container-form">
    <main class="form-signin w-100 m-auto">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <img src="../Src/Images/logo.png" alt="logolivraria" width="100%">
            <br><br><h1 class="h3 mb-3 fw-normal">Faça login aqui</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" name="username" placeholder="username">
                <label for="floatingInput">Username</label>
            </div><br>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Senha</label>
            </div>

            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Lembrar senha
                </label>
                <a href="">
                    <p> Esqueceu sua senha? Clique aqui! </p>
                </a>
            </div>

            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="session-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Manter a sessão iniciada
                </label><br><br>
                <button class="btn btn-primary w-100 py-2" type="submit" name="login">Entrar</button>
                <a href="https://www.gmail.com">Continuar com o Google</a>

                <div id="email-help" class="form-text"> Não tem conta? </div>
                <button type="button" class="btn btn-bd-primary"
                        onclick="window.location.href='registro.php'"> Registre-se
                </button>
                <?php
    if (isset($_SESSION["user_id"])) {
        echo '<div class="nav-item">
        <a href="logout.php" id="botao_sair"> Logout</a>
              </div>';
    }
    ?>
            </div>
        </form>
    </main>
</div>

<script src="../Script/java_register.js"></script>
</body>
</html>
  