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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
    } else {
        echo "Erro: Usuário não está logado.";
        exit();
    }

    $sql = "SELECT * FROM cart WHERE user_id = ?";
    $stmt_cart = $conn->prepare($sql);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();

    $order_date = date('Y-m-d');

    if ($result_cart->num_rows > 0) {

        $conn->begin_transaction();
        $stmt_order = $conn->prepare("INSERT INTO orders (user_id, cart_id, total_price, order_date, quantity, product_id) VALUES (?, ?, ?, ?, ?, ?)");

        $cart_data = array();

        while ($row = $result_cart->fetch_assoc()) {

            $cart_data[] = $row;

            $cart_id = $row["cart_id"];
            $product_id = $row["product_id"];
            $quantity = $row["quantity"];
            
            $sql_product = "SELECT price FROM products WHERE product_id = ?";
            $stmt_product = $conn->prepare($sql_product);
            $stmt_product->bind_param("i", $product_id);
            $stmt_product->execute();
            $result_product = $stmt_product->get_result();
            
            if ($result_product->num_rows > 0) {
                $product_row = $result_product->fetch_assoc();
                $price = $product_row["price"];
                $total_price = $price * $quantity;
            } else {
                $price = 0;
                $total_price = 0;
            }

            $stmt_order->bind_param("iidsii", $user_id, $cart_id, $total_price, $order_date, $quantity, $product_id);
            $stmt_order->execute();
        }
        $conn->commit();

        $conn->begin_transaction();

        foreach ($cart_data as $row) {
            $product_id = $row["product_id"];
            $quantity = $row["quantity"];

            $sql_update_stock = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
            $stmt_update_stock = $conn->prepare($sql_update_stock);
            $stmt_update_stock->bind_param("ii", $quantity, $product_id);
            $stmt_update_stock->execute();
        }

        $conn->commit();

        $stmt_order->close();

        $_SESSION['ordersuccess'] = "A Livraria MagicBook agradece a preferência, sua encomenda foi realizada com sucesso!";
                header("Location: carrinho.php");
                exit;
    } else {
        echo "Erro: Carrinho vazio.";
    }
}

$conn->close();
?>
