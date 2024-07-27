<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_magicbook";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $birthday = $_POST["birthday"];
    $address = $_POST["address"];
    $nif = $_POST["nif"];
    $password = $_POST['password'];
    $pass_confirm = $_POST['pass_confirm'];

    if (isset($_FILES['picture']) && !empty($_FILES['picture']))
    {

        $picture = "../Uploads/".$_FILES["picture"]["name"];
        move_uploaded_file($_FILES["picture"]["tmp_name"], $picture);
    }else{
        $picture ='';
    }

        $sql = "INSERT INTO users (name, surname, username, email, phone, birthday, address, nif, password, pass_confirm, user_type, picture)
                VALUES ('$name', '$surname', '$username', '$email', '$phone', '$birthday', '$address', '$nif', '$password', '$pass_confirm', 'user', '$picture')";

$stmt = $conn->prepare($sql);

        if ($conn->query($sql) === TRUE) {
            echo "Registro bem-sucedido.";
            header("Location: login.php");
        } else {
            echo "Erro ao registrar: " . $conn->error;
        }
    }
$conn->close();

