<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "library_magicbook";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("A conexão falhou: " . $conn->connect_error);
    }

    if(!isset($user_id) || !isset($product_id)) {
        echo "ID do usuário ou ID do produto não está definido.";
        exit(); 
    }

    $sql_remover_favorito = "DELETE FROM favorites WHERE product_id = $product_id AND user_id = $user_id";

    if ($conn->query($sql_remover_favorito) === TRUE) {
        header("Location: favorites.php");
        exit();
    } else {
        echo "Erro ao remover favorito: " . $conn->error;
    }

    $conn->close();
} else {
    echo "ID do produto não fornecido.";
}
?>
