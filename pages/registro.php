<?php
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("A conexão falhou: " . $conn->connect_error);
}
$conn->close();
?>

<!doctype html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style/style.css">
    <link
     rel="stylesheet"
     href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <title>Página de Registro - Livraria Magic Book</title>
    <link rel="canonical" href="https://intl-tel-input.com/examples/lookup-country.html" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css">
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
      
<form class="container-register" action="../Pages/register_user.php" method="post" onsubmit="return registrarUsuario()" enctype="multipart/form-data">
        <h1> Registro</h1>
        <div class="form-floating">
        <input type="text" class="form-control" name= "name" id="name" placeholder="Nome">
        <label for="name">Nome</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" name= "surname" id="surname" placeholder="Apelido">
        <label for="Surname">Apelido</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" name= "username" id="username" placeholder="Username">
        <label for="Username">Username</label>
      </div> <div id="mensagemErro" style="color: red;"></div>
      <div class="form-floating">
        <input type="email" class="form-control" name= "email" id="email" placeholder="name@example.com">
        <label for="Email">Email</label>
      </div>
      <div class="form-floating">
        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Telefone" maxlength="15">
      </div>
      <div class="form-floating">
        <input type="date" class="form-control"  name= "birthday" id="birthday" placeholder="Data de Nascimento" required>
        <label for="date">Data de Nascimento</label>
      </div>
         <div class="form-floating">
        <input type="text" class="form-control" name= "address" id="address" placeholder="Morada">
        <label for="address">Morada</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" name= "nif" id="nif" placeholder="NIF">
        <label for="nif">NIF</label>
      </div>
      <div class="input-group mb-3">
        <input type="file" class="form-control" name="picture" id="inputGroupFile02" accept="image/*">
        <label class="input-group-text" for="inputGroupFile02">Upload Imagem</label>
    </div>
      <div class="form-floating">
        <input type="password" class="form-control" name= "password" id="password" placeholder="Senha">
        <label for="password">Senha</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" name= "pass_confirm" id="pass_confirm" placeholder="Confirmar Senha">
        <label for="pass_confirm">Confirmar senha</label>
      </div>
      <div class="botoes">
        <button type="submit" class="btn btn-bd-primary" style="float: left;"> Registrar </button>
        <div id="conta-login" class="form-text" style="float: right; font-size: 15px; color: beige;"> Já tem conta? <button type="submit" class="btn btn-bd-primary" onclick="window.location.href='login.php'"> Faça login </button></div>
      </div></form>
      <script>

const input = document.querySelector("#phone");
window.intlTelInput(input, {
  initialCountry: "auto",
  geoIpLookup: callback => {
    fetch("https://ipapi.co/json")
      .then(res => res.json())
      .then(data => callback(data.country_code))
      .catch(() => callback("us"));
  },
});

function registrarUsuario() {
    var name = document.getElementById('name').value;
    var surname = document.getElementById('surname').value;
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    var phone = document.getElementById('phone').value;
    var address = document.getElementById('address').value;
    var nif = document.getElementById('nif').value;
    var password = document.getElementById('password').value;
    var pass_confirm = document.getElementById('pass_confirm').value;
    var birthday = document.getElementById('birthday').value;

    if (name === '' || surname === '' || username === '' || email === '' || phone === '' || address === '' || nif === '' || password === '' || pass_confirm === '') {
        alert('Por favor, preencha todos os campos.');
        return false;
    }

    var telefoneValido = /^\+\d{1,3}\s\d{9}$/.test(phone);
if (!telefoneValido) {
    alert("Por favor, insira o telefone no formato correto: +00 123456789");
    return false;
}

    function validarEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    if (!validarEmail(email)) {
        alert('Por favor, insira um e-mail válido.');
        return false;
    }

    if (password !== pass_confirm) {
        alert('As senhas não coincidem. Por favor, digite senhas iguais.');
        return false;
    }

    var idade = calcularIdade(birthday);
    if (idade < 18) {
        alert('Desculpe, você deve ter pelo menos 18 anos para se registrar.');
        return false;
    }

    alert('Usuário registrado com sucesso!');
    return true;
}

    function calcularIdade(birthday) {
        var hoje = new Date();
        var dataNascimento = new Date(birthday);
        var idade = hoje.getFullYear() - dataNascimento.getFullYear();
        var meses = hoje.getMonth() - dataNascimento.getMonth();
    
        if (meses < 0 || (meses === 0 && hoje.getDate() < dataNascimento.getDate())) {
            idade--;
        }
    
        return idade;
    }

    document.addEventListener("DOMContentLoaded", function () {
        
        document.querySelectorAll('.add-to-cart-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                form.submit();
            });
        });
    });
    </script>
      </body>
      </html>