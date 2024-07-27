<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    if (isset($_POST["cart_id"]) && isset($_POST["quantity"])) {
        $cart_id = $_POST["cart_id"];
        $new_quantity = $_POST["quantity"];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library_magicbook";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        $sql_stock = "SELECT products.stock, cart.quantity, products.price FROM cart
                      INNER JOIN products ON cart.product_id = products.product_id
                      WHERE cart.cart_id = $cart_id";
        $result_stock = $conn->query($sql_stock);

        if ($result_stock->num_rows > 0) {
            $row_stock = $result_stock->fetch_assoc();
            $stock = $row_stock["stock"];
            $cart_quantity = $row_stock["quantity"];
            $price_per_item = $row_stock["price"];

            if ($new_quantity <= $stock) {
                
                $sql_update = "UPDATE cart SET quantity = $new_quantity WHERE cart_id = $cart_id AND user_id = $user_id";

                if ($conn->query($sql_update) === TRUE) {
                    
                    header("Location: carrinho.php");
                    exit;
                }
            } else {
                $_SESSION['updateErro'] = "A quantidade selecionada excede o estoque disponível!";
                header("Location: carrinho.php");
                exit;
            }
        }

        $conn->close();
    } } else {
    header("Location: carrinho.php");
    exit;
}
?>