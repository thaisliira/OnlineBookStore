<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT image, product_id, product_name, product_desc, price, author FROM products";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $product_id = $_POST["product_id"];

        $sql = "INSERT INTO favorites (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $user_id, $product_id);
            if ($stmt->execute()) {
                
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit;
            } else {
                echo "Erro ao adicionar item aos favoritos.";
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