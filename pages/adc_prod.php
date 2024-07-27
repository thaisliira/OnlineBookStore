<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product"];
    $product_desc = $_POST["product_desc"];
    $price = $_POST["price"];
    $author = $_POST["author"];
    $category = $_POST["category"];
    $stock = $_POST["stock"];

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $target_dir = "../Uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Desculpe, houve um erro ao fazer o upload do arquivo.";
            exit;
        }

        $image = $target_file;
    } else {
        $image = '';
    }

    $sql = "INSERT INTO products (product_name, product_desc, price, author, category, stock, image)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssis", $product_name, $product_desc, $price, $author, $category, $stock, $image);

    if ($stmt->execute()) {
        header("Location: editar_prod.php");
        exit;
    } else {
        echo "Erro ao adicionar o produto: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
