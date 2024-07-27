<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password, $dbname);

$sqlRomance = "SELECT image, product_id, product_name, product_desc, price, author FROM products WHERE category = 'romance'";
$resultRomance = $conn->query($sqlRomance);

$sqlBiografia = "SELECT image, product_id, product_name, product_desc, price, author FROM products WHERE category = 'biografia'";
$resultBiografia = $conn->query($sqlBiografia);

$sqlInfantil = "SELECT image, product_id, product_name, product_desc, price, author FROM products WHERE category = 'infantil'";
$resultInfantil = $conn->query($sqlInfantil);

$sqlHumor = "SELECT image, product_id, product_name, product_desc, price, author FROM products WHERE category = 'humor'";
$resultHumor = $conn->query($sqlHumor);

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
    <title>Nossa Coleção - Livraria Magic Book</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .carousel-item img {
            display: block;
            margin: 5px;
            max-height: 300px;
        }
        .carousel-caption {
            text-align: center;
        }
        h1, h2 {
            color: white;
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

    <section class="py-3 text-center container">
    <div class="row py-lg-2">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1>Toda Nossa Coleção</h1>
        </div>
    </div>
    </section>
<div class="bd-example">
<div id="romances-carousel">
        <h2 class="text-center">Romances</h2><br>
        <div id="carouselRomance" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($resultRomance as $index => $item) : ?>
                    <div data-bs-target="#carouselRomance" data-bs-slide-to="<?php echo $index; ?>" <?php echo ($index === 0) ? 'class="active"' : ''; ?>></div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($resultRomance as $index => $item) : ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" class="d-block w-100" style="max-height: 350px; object-fit: contain;" alt="<?php echo $item['product_name']; ?>">
                            </div>
                            <div class="col-md-5">
                                <div class="item-details" style="color: azure;">
                                    <h5 class="item-name"><a href="detalhes_produto.php?id=<?php echo $item['product_id']; ?>"><?php echo $item['product_name']; ?></a></h5>
                                    <p class="item-price">Preço: <?php echo $item['price']; ?>€</p>
                                    <p class="item-description"><?php echo substr($item['product_desc'], 0, 200); ?>...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselRomance" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselRomance" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div></div><br>
    <div class="bd-example">
    <div id="biografias-carousel">
        <h2 class="text-center">Biografias</h2><br>
        <div id="carouselBiografia" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($resultBiografia as $index => $item) : ?>
                    <div data-bs-target="#carouselBiografia" data-bs-slide-to="<?php echo $index; ?>" <?php echo ($index === 0) ? 'class="active"' : ''; ?>></div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($resultBiografia as $index => $item) : ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" class="d-block w-100" style="max-height: 350px; object-fit: contain;" alt="<?php echo $item['product_name']; ?>">
                            </div>
                            <div class="col-md-5">
                                <div class="item-details" style="color: azure;">
                                <h5 class="item-name"><a href="detalhes_produto.php?id=<?php echo $item['product_id']; ?>"><?php echo $item['product_name']; ?></a></h5>
                                    <p class="item-price">Preço: <?php echo $item['price']; ?>€</p>
                                    <p class="item-description"><?php echo substr($item['product_desc'], 0, 200); ?>...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselBiografia" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselBiografia" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div></div><br>
    <div class="bd-example">
    <div id="infantil-carousel">
        <h2 class="text-center">Infantil</h2><br>
        <div id="carouselInfantil" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($resultInfantil as $index => $item) : ?>
                    <div data-bs-target="#carouselInfantil" data-bs-slide-to="<?php echo $index; ?>" <?php echo ($index === 0) ? 'class="active"' : ''; ?>></div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($resultInfantil as $index => $item) : ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" class="d-block w-100" style="max-height: 350px; object-fit: contain;" alt="<?php echo $item['product_name']; ?>">
                            </div>
                            <div class="col-md-5">
                                <div class="item-details" style="color: azure;">
                                <h5 class="item-name"><a href="detalhes_produto.php?id=<?php echo $item['product_id']; ?>"><?php echo $item['product_name']; ?></a></h5>
                                    <p class="item-price">Preço: <?php echo $item['price']; ?>€</p>
                                    <p class="item-description"><?php echo substr($item['product_desc'], 0, 200); ?>...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselInfantil" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselInfantil" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div></div><br>
    <div class="bd-example">
    <div id="infantil-carousel">
        <h2 class="text-center">Humor</h2><br>
        <div id="carouselHumor" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($resultHumor as $index => $item) : ?>
                    <div data-bs-target="#carouselHumor" data-bs-slide-to="<?php echo $index; ?>" <?php echo ($index === 0) ? 'class="active"' : ''; ?>></div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($resultHumor as $index => $item) : ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>" class="d-block w-100" style="max-height: 350px; object-fit: contain;" alt="<?php echo $item['product_name']; ?>">
                            </div>
                            <div class="col-md-5">
                                <div class="item-details" style="color: azure;">
                                <h5 class="item-name"><a href="detalhes_produto.php?id=<?php echo $item['product_id']; ?>"><?php echo $item['product_name']; ?></a></h5>
                                    <p class="item-price">Preço: <?php echo $item['price']; ?>€</p>
                                    <p class="item-description"><?php echo substr($item['product_desc'], 0, 200); ?>...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselHumor" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselHumor" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>
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
