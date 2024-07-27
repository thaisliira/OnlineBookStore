<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: admin_page.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("A conexão falhou: " . $conn->connect_error);
}

$selectedUserId = isset($_POST['selected_user_id']) ? $_POST['selected_user_id'] : null;

if ($selectedUserId !== null) {
    $sqlGetUsers = "SELECT user_id, name, surname, username, password, email FROM users WHERE user_id = ?";
    $stmtGetUsers = $conn->prepare($sqlGetUsers);
    $stmtGetUsers->bind_param("i", $selectedUserId);
    $stmtGetUsers->execute();

    if ($stmtGetUsers === false) {
        die("Erro ao executar a consulta preparada: " . $stmtGetUsers->error);
    }

    $resultGetUsers = $stmtGetUsers->get_result();

    if ($resultGetUsers->num_rows > 0) {
        $row = $resultGetUsers->fetch_assoc();
    } else {
        echo "Usuário não encontrado.";
    }

    $stmtGetUsers->close();
}

$sqlGetAllUsers = "SELECT user_id, name FROM users";
$resultGetAllUsers = $conn->query($sqlGetAllUsers);

if (!$resultGetAllUsers) {
    die("Erro na consulta SQL: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <title>Editar Produtos - Livraria Magic Book</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .secao {
            background-color: rgba(255, 255, 255, 0.9); 
            border-radius: 10px; 
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); 
            padding: 20px; 
            margin-bottom: 20px; 
        }

        .secao h2 {
            color: black; 
            margin-bottom: 15px; 
        }

        .btn-sair {
            margin-top: 20px;
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
</nav><br>
    <section class="py-3 text-center container secao">
    <div class="row py-lg-2">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Gestão de Usuários</h1>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col-lg-12 secao">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="selected_user_id">Selecione o usuário:</label>
                <select class="form-select" name="selected_user_id" id="selected_user_id">
                    <?php
                    while ($user = $resultGetAllUsers->fetch_assoc()) {
                        echo "<option value='{$user['user_id']}'" . ($selectedUserId == $user['user_id'] ? " selected" : "") . ">{$user['name']}</option>";
                    }
                    ?>
                </select>
                <br><input type="submit" class="btn btn-primary" value="Selecionar">
            </form>
<br>
            <?php if (isset($row)): ?>
                <form method="post" action="salvar_eduser.php">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Nome</th>
                            <td><input type="text" class="form-control" name="name" id="name" value="<?php echo $row['name']; ?>" /></td>
                        </tr>
                        <tr>
                            <th>Sobrenome</th>
                            <td><input type="text" class="form-control" name="surname" id="surname" value="<?php echo $row['surname']; ?>" /></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><input type="text" class="form-control" name="username" id="username" value="<?php echo $row['username']; ?>" /></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><input type="text" class="form-control" name="email" id="email" value="<?php echo $row['email']; ?>" /></td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td><input type="text" class="form-control" name="password" id="password" value="<?php echo $row['password']; ?>" /></td>
                        </tr>
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                    </table>
                        <td colspan="2"><input type="submit" class="btn btn-primary" name="Submit" value="Salvar Alterações"></td>
                </form>
            <?php endif; ?>
            <br><div>
                <p>Clique <a href="admin_page.php"><b>aqui</b></a> para voltar.</p>
            </div>
        </div>
    </div>
</div>
</div>
<footer class="text-body-primary py-5">
        <div class="container">
            <p class="mb-1">&copy; Livraria MagicBook | Todos os direitos reservados | Porto, Portugal</p>
        </div>
    </footer>
</body>
</html