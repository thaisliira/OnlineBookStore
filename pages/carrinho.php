<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library_magicbook";

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $sql = "SELECT cart.cart_id, cart.product_id, products.product_name, products.price, cart.quantity AS quantity
        FROM cart
        INNER JOIN products ON cart.product_id = products.product_id
        LEFT JOIN favorites ON cart.product_id = favorites.product_id AND cart.user_id = favorites.user_id
        WHERE cart.user_id = $user_id
        GROUP BY cart.cart_id, cart.product_id, products.product_name, products.price";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <title>Meu Carrinho - Livraria Magic Book</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <section class="h-100 h-custom" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-7">
                                <h5 class="mb-3"><a href="product.php" class="text-body"><i
                                            class="fas fa-long-arrow-alt-left me-2"></i>Continuar Comprando</a></h5>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <p class="mb-1"><b>Carrinho</p></b>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-row align-items-center">
                                                <div class="card-body" id="carrinho">
                                            <?php
                                            $total = 0; 
                                            ?>
                                            <?php if ($result->num_rows > 0) : ?>
                                <table>
                                <tr>
                                <th>Item</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                                </tr>
                                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                <td><?php echo $row["product_name"]; ?></td>
                                <td class="preco-item"><?php echo $row["price"]; ?>€</td>
                                <td class="align-middle">
                                <form method="post" action="atualizar_carrinho.php">
                                    <input type="hidden" name="cart_id" value="<?php echo $row["cart_id"]; ?>">
                                    <input type="number" name="quantity" value="<?php echo $row["quantity"]; ?>" class="form-control form-control-sm" style="width: 50px;" min="1">
                                    <button type="submit" class="btn btn-primary btn-sm mb-2">Atualizar</button>
                                </form>
                                </td>
                                <td>
                                <form method="post" action="remover_item.php">
                                    <input type="hidden" name="cart_id" value="<?php echo $row["cart_id"]; ?>">
                                    <button type="submit"class="btn btn-primary btn-sm me-1 mb-2" data-mdb-toggle="tooltit" title="Remover item"><i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <form method="post" action="add_favorites.php">
                                    <input type="hidden" name="product_id" value="<?php echo $row["product_id"]; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm mb-2" data-mdb-toggle="tooltip"title="Adicionar aos favoritos"><i class="fas fa-heart"></i>
                                    </button>
                                </form>
                                </td>
                                </tr>
                                        <?php 
                                        $preco_item = $row['price'] * $row['quantity'];
                                        $total += $preco_item;
                                        ?>
                                        <?php endwhile; ?>
                                </table>
                                                    <?php else : ?>
                                                    <p>O seu carrinho está vazio.</p>
                                                    <?php endif; ?>
                                                    <p class="text-center text-danger">
        <?php
        if (isset($_SESSION['updateErro'])) {
            echo "<div class='alert alert-danger'>{$_SESSION['updateErro']}</div>";
            unset($_SESSION['updateErro']);
        }
        ?>
    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="card bg-secondary text-white rounded-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="mb-0"><b>Detalhes do Pagamento</h5></b>
                                        </div>
                                        <p class="small mb-2">Aceitamos</p>
                                        <label class="text-white"><i class="fab fa-cc-mastercard fa-2x me-2"></i></label>
                                        <label class="text-white"><i class="fab fa-cc-visa fa-2x me-2"></i></label>
                                        <label class="text-white"><i class="fab fa-cc-amex fa-2x me-2"></i></label>
                                        <label class="text-white"><i class="fab fa-cc-paypal fa-2x"></i></label>
                                        <form class="mt-4" method="post" action="checkout.php">
                                            <div class="form-outline form-white mb-4">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
    <?php 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <input type="hidden" name="cart_id[]" value="<?php echo $row["cart_id"]; ?>">
    <?php }} ?>
                                                <input type="text" id="typeName" class="form-control form-control-lg" size="17" placeholder="Nome no Cartão" required>
                                                <label class="form-label" for="typeName">Nome do Titular do Cartão</label>
                                            </div>
                                            <div class="form-outline form-white mb-4">
                                                <input type="text" id="typeText" class="form-control form-control-lg" size="17" placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" required>
                                                <label class="form-label" for="typeText">Número do Cartão</label>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="form-outline form-white">
                                                        <input type="text" id="typeExp" class="form-control form-control-lg" placeholder="MM/YYYY" size="7" id="exp" minlength="7" maxlength="7" required>
                                                        <label class="form-label" for="typeExp">Data de Expiração</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-outline form-white">
                                                        <input type="password" id="typeText" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" required>
                                                        <label class="form-label" for="typeText">CVV</label>
                                                    </div>
                                                </div>
                                            </div>
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-2">Frete</p>
                                            <div class="card-body" id="prazo-entrega">
                                                <p>Prazo de Entrega: <span id="data-entrega"></span></p>
                                            </div>
                                            <p class="mb-2">Grátis</p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-4">
                                            <p class="mb-2">Total(Incl. taxas)</p>
                                            <p class="mb-2"><?php echo number_format($total, 2, ',', '.'); ?>€</p>
                                        </div>
                                    
    <button type="submit" class="btn btn-success btn-block btn-lg">
        <div class="d-flex justify-content-between">
            <span>Concluir Compra <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
        </div>
    </button>
    <?php
        if (isset($_SESSION['ordersuccess'])) {
            echo "<div class='alert alert-success'>{$_SESSION['ordersuccess']}</div>";
            unset($_SESSION['ordersuccess']);
        }
        ?>
</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="text-body-primary py-5">
  <div class="container">
    <p class="mb-1"> &copy; Livraria MagicBook | Todos os direitos reservados | Porto, Portugal</p>
  </div>
</footer>
</body>
<script>
    function calcularDataEntrega() {
            
            var dataAtual = new Date();
            
            var dataEntrega = new Date(dataAtual.getTime() + (10 * 24 * 60 * 60 * 1000));
            
            var dia = ("0" + dataEntrega.getDate()).slice(-2);
            var mes = ("0" + (dataEntrega.getMonth() + 1)).slice(-2);
            var ano = dataEntrega.getFullYear();
            var dataFormatada = dia + '/' + mes + '/' + ano;

            var dataEntregaElement = document.getElementById('data-entrega');
            dataEntregaElement.textContent = dataFormatada;
        }

        calcularDataEntrega();
    </script>
</html>

<?php
$conn->close();
} else {
header("Location: login.php");
exit;
}
?>

