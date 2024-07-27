<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("A conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT user_id, name, surname, username, password, email, picture FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if (!$result) {
    die ("Erro na consulta SQL: " . $conn->error);
}

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $name = $row['name'];
    $surname = $row['surname'];
    $username = $row['username'];
    $email = $row['email'];
} else {
    echo "Perfil de usuário não encontrado.";
}

function obterDadosUsers($conn) {
    $sql = "SELECT name, surname, username, password, email FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

$dadosUsers = obterDadosUsers($conn);

function obterDadosProducts($conn) {
    $sql = "SELECT product_id, product_name, author, price, product_desc, stock FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

$dadosProducts = obterDadosProducts($conn);

function obterDadosOrders($conn) {
    $sql = "SELECT o.order_date, p.product_name, o.quantity, o.total_price, u.name
            FROM orders o
            INNER JOIN products p ON o.product_id = p.product_id
            INNER JOIN users u ON o.user_id = u.user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

$dadosOrders = obterDadosOrders($conn);

$conn->close();
?>

<!doctype html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <title>Perfil do Administrador - Livraria MagicBook</title>
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
            color: #333; 
            margin-bottom: 20px; 
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
                <h1 class="fw-light"><b>Perfil do Administrador</b></h1>
                <p class="lead text-body-secondary">Olá, <?php echo $name; ?>!</p>
            </div>
        </div>
    </section>

    <div class="container conteudo">
        <div class="row">
            <div class="col-lg-12 secao">
                <h2>Usuários</h2>

                <?php if (!empty($dadosUsers)): ?>
                    <table class="table tabela-usuarios">
                        <tr>
                            <th> Nome </th>
                            <th> Sobrenome </th>
                            <th> Username </th>
                            <th> Email </th>
                            <th> Password </th>
                        </tr>
                        <?php foreach ($dadosUsers as $row): ?>
                            <tr>
                                <td><?php echo $row["name"]; ?></td>
                                <td><?php echo $row["surname"]; ?></td>
                                <td><?php echo $row["username"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td><?php echo $row["password"]; ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Nenhum resultado encontrado.</p>
                <?php endif; ?> 
                <a href='editar_users.php' class="btn btn-primary">Editar dados</a>
            </div>
        </div>


    <div class="container conteudo">
        <div class="row">
            <div class="col-lg-12 secao">
                <h2>Todos os Produtos:</h2>

                <?php if (!empty($dadosProducts)): ?>
                    <table class="table tabela-produtos">
                        <tr>
                            <th> Nome </th>
                            <th> Preço </th>
                            <th> Estoque </th>
                        </tr>
                        <?php foreach ($dadosProducts as $row): ?>
                            <tr>
                                <td><?php echo $row["product_name"]; ?></td>
                                <td><?php echo $row["price"]; ?></td>
                                <td><?php echo $row["stock"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Nenhum resultado encontrado.</p>
                <?php endif; ?> 
                <a href='editar_prod.php' class="btn btn-primary">Editar dados</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 secao">
                <h2>Todos os Pedidos:</h2>

                <?php if (!empty($dadosOrders)): ?>
                    <div class="table-responsive">
                    <table class="table tabela-pedidos">
                        <tr>
                            <th> Data do Pedido </th>
                            <th> Produto </th>
                            <th> Quantidade</th>
                            <th> Preço total </th>
                            <th> Usuário </th>
                        </tr>
                        <?php foreach ($dadosOrders as $row): ?>
                            <tr>
                                <td><?php echo $row["order_date"]; ?></td>
                                <td><?php echo $row["product_name"]; ?></td>
                                <td><?php echo $row["quantity"]; ?></td>
                                <td><?php echo $row["total_price"]; ?></td>
                                <td><?php echo $row["name"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>Nenhum resultado encontrado.</p>
                <?php endif; ?> 
            </div>
        </div>
    </div>
                </div>

    <div class="row">
        <div class="col-lg-12 text-center">
            <a href="login.php" class="btn btn-danger btn-sair">Logout</a>
        </div>
    </div>
    <footer class="text-body-primary py-5">
        <div class="container">
            <p class="mb-1">&copy; Livraria MagicBook | Todos os direitos reservados | Porto, Portugal</p>
        </div>
    </footer>
    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
