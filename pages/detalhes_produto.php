<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
}

$sql = "SELECT image, product_id, product_name, product_desc, price, author, stock FROM products WHERE product_id = '$product_id'";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $product_id = $_POST["product_id"];

        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $user_id, $product_id);
            if ($stmt->execute()) {
                echo "Item adicionado ao carrinho com sucesso!";
            } else {
                echo "Erro ao adicionar item ao carrinho.";
            }
            $stmt->close();
        } else {
            echo "Erro na preparação da declaração SQL.";
        }
    } else {
        header("Location: login.php");
        exit(); 
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <title>Livraria Magic Book</title>
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
    <main>
    <div class="album py-5 bg-body-tertiary">
    <div class="container-lg">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stock_message = ($row["stock"] > 0) ? "Em Stock" : "Sem stock";
                    $imageBase64 = base64_encode($row["image"]);
                    $imageSrc = 'data:image/jpeg;base64,' . $imageBase64;
            ?>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="<?php echo $imageSrc; ?>" alt="<?php echo $row["product_name"]; ?>" class="card-img-top img-fluid" style="object-fit: cover;  object-position: center; width: 80%; margin-top:25px; margin-bottom:25px;">
                    </div>
                        <div class="col-md-8 align-self-center">
                        <div class="card-body">
                        <h5 class="card-title"><?php echo $row["product_name"]; ?></h5>
                        <p class="card-text" style="text-align: justify"><?php echo $row["product_desc"]; ?></p>
                        <p class="card-text">Preço: <?php echo $row["price"]; ?> €</p>
                        <p class="card-text">Autor: <?php echo $row["author"]; ?></p>
                        <p class="card-text">Disponibilidade: <?php echo $stock_message; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                <form method="post" action="add_favorites.php">
                    <input type="hidden" name="product_id" value="<?php echo $row["product_id"]; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-secondary add-to-cart-btn">Adicionar aos Favoritos</button>
                </form>
                <?php if ($row["stock"] > 0) { ?>
                <form method="post" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $row["product_id"]; ?>">
                    <button type="submit" min="1" class="btn btn-sm btn-outline-secondary add-to-cart-btn">Adicionar ao Carrinho</button>
                </form>
                <?php } ?>
            </div>
    <?php
        }
    } else {
        echo "<p>Nenhum produto encontrado.</p>";
    }
    ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
        </div>
    </div>
</div>
</main>
    <footer class="text-body-secondary py-5">
        <div class="container">
            <p class="mb-1">&copy; Livraria MagicBook | Todos os direitos reservados | Porto, Portugal</p>
        </div>
    </footer>
    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
    </html>