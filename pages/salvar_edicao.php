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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE products SET product_name = ?, price = ?, stock = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdii", $product_name, $price, $stock, $product_id);

    if ($stmt->execute()) {
        header("Location: editar_prod.php");
        exit();
    } else {
        echo "Erro ao atualizar os dados do produto: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
