<?php
session_start();

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("A conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $sql = "UPDATE users SET name = ?, surname = ?, username = ?, password = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $surname, $username, $password, $email, $user_id);

    if ($stmt->execute()) {
        header("Location: editar_dadosuser.php");
        exit;
    } else {
        echo "Erro ao atualizar os dados do usuário: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

?>
