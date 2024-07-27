<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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
$user_id = $_SESSION['user_id'];

$sql = "SELECT name FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if (!$result) {
    die ("Erro na consulta SQL: " . $conn->error);
}

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
} else {
    echo "Perfil de usuário não encontrado.";
}

$sql_user = "SELECT name, surname, username, password, address, phone, nif FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {

    $user_data = $result_user->fetch_assoc();
} else {

    $user_data = [];
}

$sql_orders = "SELECT o.order_id, o.quantity, o.order_date, o.total_price, p.product_name, p.product_desc
               FROM orders o
               INNER JOIN products p ON o.product_id = p.product_id
               WHERE o.user_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

$orders = [];
while ($row = $result_orders->fetch_assoc()) {
    $orders[] = $row;
}

$stmt_orders->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Livraria Magic Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
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

        .lista-pedidos {
            list-style: none; 
            padding-left: 0; 
        }

        .lista-pedidos li {
            margin-bottom: 10px;
        }

        .btn-logout {
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
</nav>
    <br>
    <section class="py-3 text-center container secao">
        <div class="row py-lg-2">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light"><b>Perfil do Usuário</b></h1>
                <p class="lead text-body-secondary">Olá, <?php echo $name; ?>!</p>
            </div>
        </div>
    </section>
    <div class="container conteudo">
        <div class="row">
        <div class="col-lg-12 secao">
        <h2>Meus Dados</h2>
        <?php if (!empty($user_data)): ?>
            <table class="table tabela-pedidos">
                <tr>
                    <th> Nome</th>
                    <th> Sobrenome </th>
                    <th> Username </th>
                    <th> Senha </th>
                    <th> Morada</th>
                    <th> Telefone </th>
                    <th> NIF </th>
                </tr>
                <tr>
                    <td><?php echo $user_data["name"]; ?> </td>
                    <td><?php echo $user_data["surname"]; ?></td>
                    <td><?php echo $user_data["username"]; ?></td>
                    <td><?php echo $user_data["password"]; ?></td>
                    <td><?php echo $user_data["address"]; ?></td>
                    <td><?php echo $user_data["phone"]; ?></td>
                    <td><?php echo $user_data["nif"]; ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>Dados do usuário não encontrados.</p>
        <?php endif; ?>
        <a href='editar_dadosuser.php' class="btn btn-primary">Editar dados</a>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 secao">
                <h2>Meus Pedidos</h2>
                <?php if (!empty($orders)): ?>
                  <table class="table tabela-pedidos">
                        <tr>
                            <th> Data do Pedido </th>
                            <th> Produto </th>
                            <th> Quantidade</th>
                            <th> Preço total </th>
                        </tr>
                        <?php foreach ($orders as $order): ?>
                          <tr>
                                <td><?php echo $order["order_date"]; ?></td>
                                <td><?php echo $order["product_name"]; ?></td>
                                <td><?php echo $order["quantity"]; ?></td>
                                <td><?php echo $order["total_price"]; ?>€</td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                <?php else: ?>
                    <p>Nenhum pedido encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center">
            <a href="logout.php" class="btn btn-danger btn-logout">Logout</a>
        </div>
    </div>
    <footer class="text-body-secondary py-5">
  <div class="container">
    <p class="mb-1"> &copy; Livraria MagicBook | Todos os direitos reservados | Porto, Portugal</p>
  </div>
</footer>
</body>
</html>
